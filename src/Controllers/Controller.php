<?php

namespace Controllers;

/**
 * Controller Base - Classe abstrata para todos os controllers
 */
abstract class Controller
{
    /**
     * Renderiza uma view
     *
     * @param string $view Nome da view (ex: 'auth/login')
     * @param array $data Dados para passar para a view
     */
    protected function view($view, $data = [])
    {
        // Extrai variáveis do array $data
        extract($data);
        
        // Caminho da view
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            die("View não encontrada: {$view}");
        }
        
        require_once $viewPath;
    }

    /**
     * Redireciona para outra URL
     *
     * @param string $url URL para redirecionar
     */
    protected function redirect($url)
    {
        $config = require __DIR__ . '/../../config/app.php';
        $baseUrl = $config['base_url'];
        
        header("Location: {$baseUrl}{$url}");
        exit;
    }

    /**
     * Retorna JSON
     *
     * @param mixed $data Dados para retornar em JSON
     * @param int $statusCode Código HTTP
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Verifica se o usuário está autenticado
     */
    protected function requireAuth()
    {
        if (!isset($_SESSION['gestor_id'])) {
            $this->redirect('/login');
        }
    }

    /**
     * Retorna o gestor autenticado
     *
     * @return array|null
     */
    protected function getAuthUser()
    {
        if (isset($_SESSION['gestor_id'])) {
            $gestorModel = new \Models\Gestor();
            return $gestorModel->find($_SESSION['gestor_id']);
        }
        return null;
    }

    /**
     * Sanitiza dados de entrada
     *
     * @param mixed $data
     * @return mixed
     */
    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Define uma mensagem flash na sessão
     *
     * @param string $key Chave da mensagem
     * @param mixed $value Valor da mensagem
     */
    protected function setFlash($key, $value)
    {
        $_SESSION['flash'][$key] = $value;
    }

    /**
     * Obtém e remove uma mensagem flash da sessão
     *
     * @param string $key Chave da mensagem
     * @return mixed|null
     */
    protected function getFlash($key)
    {
        if (isset($_SESSION['flash'][$key])) {
            $value = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $value;
        }
        return null;
    }

    /**
     * Valida upload de imagem
     *
     * @param array $file Arquivo do $_FILES
     * @return array ['valid' => bool, 'errors' => array, 'filename' => string]
     */
    protected function validateImage($file)
    {
        $config = require __DIR__ . '/../../config/app.php';
        $errors = [];

        // Verifica se o arquivo foi enviado
        if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return ['valid' => true, 'errors' => [], 'filename' => null];
        }

        // Verifica erros de upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Erro ao fazer upload do arquivo';
            return ['valid' => false, 'errors' => $errors];
        }

        // Verifica tamanho
        if ($file['size'] > $config['upload_max_size']) {
            $errors[] = 'Arquivo muito grande. Máximo: ' . ($config['upload_max_size'] / 1024 / 1024) . 'MB';
        }

        // Verifica tipo
        if (!in_array($file['type'], $config['upload_allowed_types'])) {
            $errors[] = 'Tipo de arquivo não permitido';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Faz upload de imagem
     *
     * @param array $file Arquivo do $_FILES
     * @param string $prefix Prefixo do nome do arquivo
     * @return array ['success' => bool, 'filename' => string|null, 'errors' => array]
     */
    protected function uploadImage($file, $prefix = 'img')
    {
        $validation = $this->validateImage($file);
        
        if (!$validation['valid']) {
            return ['success' => false, 'filename' => null, 'errors' => $validation['errors']];
        }

        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return ['success' => true, 'filename' => null, 'errors' => []];
        }

        $config = require __DIR__ . '/../../config/app.php';
        
        // Gera nome único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $prefix . '_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $destination = $config['upload_path'] . $filename;

        // Move o arquivo
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => true, 'filename' => $filename, 'errors' => []];
        }

        return ['success' => false, 'filename' => null, 'errors' => ['Erro ao salvar o arquivo']];
    }
}
