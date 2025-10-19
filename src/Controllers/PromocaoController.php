<?php

namespace Controllers;

use Models\Promocao;

class PromocaoController extends Controller
{
    private $promocaoModel;

    public function __construct()
    {
        $this->promocaoModel = new Promocao();
    }

    /**
     * Lista todas as promoções
     */
    public function index()
    {
        $this->requireAuth();
        
        $promocoes = $this->promocaoModel->all('prm_data_inicio DESC');
        
        return $this->view('promocoes/index', [
            'promocoes' => $promocoes
        ]);
    }

    /**
     * Exibe formulário para nova promoção
     */
    public function create()
    {
        $this->requireAuth();
        
        return $this->view('promocoes/create');
    }

    /**
     * Salva nova promoção
     */
    public function store()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/promocoes');
        }

        $nome = $this->sanitize($_POST['prm_nome'] ?? '');
        $descricao = $this->sanitize($_POST['prm_descricao'] ?? '');
        $tipo = $_POST['prm_tipo'] ?? '';
        $valor = floatval(str_replace(',', '.', $_POST['prm_valor'] ?? '0'));
        $dataInicio = $_POST['prm_data_inicio'] ?? '';
        $dataFim = $_POST['prm_data_fim'] ?? '';
        $ativo = isset($_POST['ativo']) ? 1 : 0;
        
        // Validações
        $errors = [];
        if (empty($nome)) $errors[] = 'Nome da promoção é obrigatório!';
        if (empty($tipo)) $errors[] = 'Tipo de promoção é obrigatório!';
        if ($valor <= 0) $errors[] = 'Valor deve ser maior que zero!';
        if (empty($dataInicio)) $errors[] = 'Data de início é obrigatória!';
        if (empty($dataFim)) $errors[] = 'Data de fim é obrigatória!';
        if ($dataInicio && $dataFim && $dataInicio > $dataFim) {
            $errors[] = 'Data de início deve ser anterior à data de fim!';
        }
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            return $this->redirect('/promocoes/add');
        }

        try {
            $this->promocaoModel->create([
                'prm_nome' => $nome,
                'prm_descricao' => $descricao,
                'prm_tipo' => $tipo,
                'prm_valor' => $valor,
                'prm_data_inicio' => $dataInicio,
                'prm_data_fim' => $dataFim,
                'ativo' => $ativo
            ]);
            
            $this->setFlash('success', 'Promoção criada com sucesso!');
            return $this->redirect('/promocoes');
            
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erro ao criar promoção: ' . $e->getMessage());
            return $this->redirect('/promocoes/add');
        }
    }

    /**
     * Exibe formulário de edição
     */
    public function edit($id)
    {
        $this->requireAuth();
        
        $promocao = $this->promocaoModel->find($id);
        
        if (!$promocao) {
            $this->setFlash('error', 'Promoção não encontrada!');
            return $this->redirect('/promocoes');
        }

        return $this->view('promocoes/edit', [
            'promocao' => $promocao
        ]);
    }

    /**
     * Atualiza promoção
     */
    public function update($id)
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/promocoes');
        }

        $promocao = $this->promocaoModel->find($id);
        
        if (!$promocao) {
            $this->setFlash('error', 'Promoção não encontrada!');
            return $this->redirect('/promocoes');
        }

        $nome = $this->sanitize($_POST['prm_nome'] ?? '');
        $descricao = $this->sanitize($_POST['prm_descricao'] ?? '');
        $tipo = $_POST['prm_tipo'] ?? '';
        $valor = floatval(str_replace(',', '.', $_POST['prm_valor'] ?? '0'));
        $dataInicio = $_POST['prm_data_inicio'] ?? '';
        $dataFim = $_POST['prm_data_fim'] ?? '';
        $ativo = isset($_POST['ativo']) ? 1 : 0;
        
        // Validações
        $errors = [];
        if (empty($nome)) $errors[] = 'Nome da promoção é obrigatório!';
        if (empty($tipo)) $errors[] = 'Tipo de promoção é obrigatório!';
        if ($valor <= 0) $errors[] = 'Valor deve ser maior que zero!';
        if (empty($dataInicio)) $errors[] = 'Data de início é obrigatória!';
        if (empty($dataFim)) $errors[] = 'Data de fim é obrigatória!';
        if ($dataInicio && $dataFim && $dataInicio > $dataFim) {
            $errors[] = 'Data de início deve ser anterior à data de fim!';
        }
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            return $this->redirect('/promocoes/edit/' . $id);
        }

        try {
            $this->promocaoModel->update($id, [
                'prm_nome' => $nome,
                'prm_descricao' => $descricao,
                'prm_tipo' => $tipo,
                'prm_valor' => $valor,
                'prm_data_inicio' => $dataInicio,
                'prm_data_fim' => $dataFim,
                'ativo' => $ativo
            ]);
            
            $this->setFlash('success', 'Promoção atualizada com sucesso!');
            return $this->redirect('/promocoes');
            
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erro ao atualizar promoção: ' . $e->getMessage());
            return $this->redirect('/promocoes/edit/' . $id);
        }
    }

    /**
     * Exclui promoção
     */
    public function delete($id)
    {
        $this->requireAuth();
        
        $promocao = $this->promocaoModel->find($id);
        
        if (!$promocao) {
            $this->setFlash('error', 'Promoção não encontrada!');
            return $this->redirect('/promocoes');
        }

        try {
            $this->promocaoModel->delete($id);
            
            $this->setFlash('success', 'Promoção excluída com sucesso!');
            return $this->redirect('/promocoes');
            
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erro ao excluir promoção: ' . $e->getMessage());
            return $this->redirect('/promocoes');
        }
    }
}