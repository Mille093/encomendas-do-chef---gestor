<?php

namespace Controllers;

use Models\Gestor;

/**
 * AuthController - Gerencia autenticação
 */
class AuthController extends Controller
{
    private $gestorModel;

    public function __construct()
    {
        $this->gestorModel = new Gestor();
    }

    /**
     * Exibe o formulário de login
     */
    public function showLogin()
    {
        // Se já estiver logado, redireciona para o dashboard
        if (isset($_SESSION['gestor_id'])) {
            $this->redirect('/dashboard');
        }

        $error = $this->getFlash('error');
        $oldEmail = $this->getFlash('old_email');

        $this->view('auth/login', [
            'error' => $error,
            'oldEmail' => $oldEmail
        ]);
    }

    /**
     * Processa o login
     */
    public function login()
    {
        // Verifica se já está logado
        if (isset($_SESSION['gestor_id'])) {
            $this->redirect('/dashboard');
        }

        // Coleta e sanitiza dados
        $email = $this->sanitize($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        // Validações
        $errors = [];

        if (empty($email)) {
            $errors[] = 'Email é obrigatório';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        }

        if (empty($senha)) {
            $errors[] = 'Senha é obrigatória';
        }

        if (!empty($errors)) {
            $this->setFlash('error', implode(', ', $errors));
            $this->setFlash('old_email', $email);
            $this->redirect('/login');
        }

        // Tenta autenticar
        $gestor = $this->gestorModel->verificarCredenciais($email, $senha);

        if ($gestor) {
            // Login bem-sucedido
            $_SESSION['gestor_id'] = $gestor['gst_id'];
            $_SESSION['gestor_nome'] = $gestor['gst_nome'];
            $_SESSION['gestor_email'] = $gestor['gst_email'];
            
            // Log de atividade
            error_log("Login realizado: {$email} em " . date('Y-m-d H:i:s'));
            
            $this->redirect('/dashboard');
        } else {
            // Credenciais inválidas
            $this->setFlash('error', 'Email ou senha incorretos');
            $this->setFlash('old_email', $email);
            
            // Log de tentativa falha
            error_log("Tentativa de login falha: {$email} em " . date('Y-m-d H:i:s'));
            
            $this->redirect('/login');
        }
    }

    /**
     * Faz logout
     */
    public function logout()
    {
        // Log de atividade
        if (isset($_SESSION['gestor_email'])) {
            error_log("Logout realizado: {$_SESSION['gestor_email']} em " . date('Y-m-d H:i:s'));
        }

        // Destrói a sessão
        session_destroy();
        
        // Inicia nova sessão e redireciona
        session_start();
        $this->setFlash('success', 'Você saiu com sucesso');
        
        $this->redirect('/login');
    }
}
