<?php

namespace Controllers;

use Models\Categoria;

class CategoriaController extends Controller
{
    private $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new Categoria();
    }

    /**
     * Lista todas as categorias
     */
    public function index()
    {
        $this->requireAuth();
        
        $categorias = $this->categoriaModel->all('cat_nome ASC');
        
        return $this->view('categorias/index', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Exibe formulário para nova categoria
     */
    public function create()
    {
        $this->requireAuth();
        
        return $this->view('categorias/create');
    }

    /**
     * Salva nova categoria
     */
    public function store()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/categorias');
        }

        $nome = $this->sanitize($_POST['cat_nome'] ?? '');
        
        // Validação
        if (empty($nome)) {
            $this->setFlash('error', 'Nome da categoria é obrigatório!');
            return $this->redirect('/categorias/add');
        }

        try {
            $this->categoriaModel->create([
                'cat_nome' => $nome
            ]);
            
            $this->setFlash('success', 'Categoria criada com sucesso!');
            return $this->redirect('/categorias');
            
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erro ao criar categoria: ' . $e->getMessage());
            return $this->redirect('/categorias/add');
        }
    }

    /**
     * Exibe formulário de edição
     */
    public function edit($id)
    {
        $this->requireAuth();
        
        $categoria = $this->categoriaModel->find($id);
        
        if (!$categoria) {
            $this->setFlash('error', 'Categoria não encontrada!');
            return $this->redirect('/categorias');
        }

        return $this->view('categorias/edit', [
            'categoria' => $categoria
        ]);
    }

    /**
     * Atualiza categoria
     */
    public function update($id)
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/categorias');
        }

        $categoria = $this->categoriaModel->find($id);
        
        if (!$categoria) {
            $this->setFlash('error', 'Categoria não encontrada!');
            return $this->redirect('/categorias');
        }

        $nome = $this->sanitize($_POST['cat_nome'] ?? '');
        
        // Validação
        if (empty($nome)) {
            $this->setFlash('error', 'Nome da categoria é obrigatório!');
            return $this->redirect('/categorias/edit/' . $id);
        }

        try {
            $this->categoriaModel->update($id, [
                'cat_nome' => $nome
            ]);
            
            $this->setFlash('success', 'Categoria atualizada com sucesso!');
            return $this->redirect('/categorias');
            
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erro ao atualizar categoria: ' . $e->getMessage());
            return $this->redirect('/categorias/edit/' . $id);
        }
    }

    /**
     * Exclui categoria
     */
    public function delete($id)
    {
        $this->requireAuth();
        
        $categoria = $this->categoriaModel->find($id);
        
        if (!$categoria) {
            $this->setFlash('error', 'Categoria não encontrada!');
            return $this->redirect('/categorias');
        }

        try {
            $this->categoriaModel->delete($id);
            
            $this->setFlash('success', 'Categoria excluída com sucesso!');
            return $this->redirect('/categorias');
            
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erro ao excluir categoria: ' . $e->getMessage());
            return $this->redirect('/categorias');
        }
    }
}