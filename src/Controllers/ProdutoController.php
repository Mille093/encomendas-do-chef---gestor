<?php

namespace Controllers;

use Models\Produto;
use Models\Categoria;

class ProdutoController extends Controller
{
    private $produtoModel;
    private $categoriaModel;

    public function __construct()
    {
        $this->produtoModel = new Produto();
        $this->categoriaModel = new Categoria();
    }

    /**
     * Lista todos os produtos
     */
    public function index()
    {
        $this->requireAuth();
        
        // Buscar produtos com suas categorias
        $pdo = \Core\Database::getInstance()->getConnection();
        $stmt = $pdo->query(
            "SELECT p.*, c.cat_nome 
             FROM produtos p 
             LEFT JOIN categorias c ON p.cat_codigo = c.cat_codigo 
             ORDER BY p.prod_nome ASC"
        );
        $produtos = $stmt->fetchAll();
        
        return $this->view('produtos/index', [
            'produtos' => $produtos
        ]);
    }

    /**
     * Exibe formulário para novo produto
     */
    public function create()
    {
        $this->requireAuth();
        
        $categorias = $this->categoriaModel->all('cat_nome ASC');
        
        return $this->view('produtos/create', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Salva novo produto
     */
    public function store()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/produtos');
        }

        $nome = $this->sanitize($_POST['prod_nome'] ?? '');
        $descricao = $this->sanitize($_POST['prod_descricao'] ?? '');
        $preco = floatval(str_replace(',', '.', $_POST['prod_preco'] ?? '0'));
        $categoria = $_POST['cat_codigo'] ?? null;
        $ativo = isset($_POST['prod_ativo']) ? 1 : 0;
        
        // Validações
        $errors = [];
        if (empty($nome)) $errors[] = 'Nome do produto é obrigatório!';
        if ($preco <= 0) $errors[] = 'Preço deve ser maior que zero!';
        if (empty($categoria)) $errors[] = 'Categoria é obrigatória!';
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            return $this->redirect('/produtos/add');
        }

        // Upload de imagem
        $imagemNome = null;
        if (!empty($_FILES['prod_imagem']['name'])) {
            try {
                $imagemNome = $this->uploadImage($_FILES['prod_imagem'], 'produtos');
            } catch (\Exception $e) {
                $this->setFlash('error', 'Erro no upload da imagem: ' . $e->getMessage());
                return $this->redirect('/produtos/add');
            }
        }

        try {
            $this->produtoModel->create([
                'prod_nome' => $nome,
                'prod_descricao' => $descricao,
                'prod_preco' => $preco,
                'cat_codigo' => $categoria,
                'prod_ativo' => $ativo,
                'prod_imagem' => $imagemNome
            ]);
            
            $this->setFlash('success', 'Produto criado com sucesso!');
            return $this->redirect('/produtos');
            
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erro ao criar produto: ' . $e->getMessage());
            return $this->redirect('/produtos/add');
        }
    }

    /**
     * Exibe formulário de edição
     */
    public function edit($id)
    {
        $this->requireAuth();
        
        $produto = $this->produtoModel->find($id);
        
        if (!$produto) {
            $this->setFlash('error', 'Produto não encontrado!');
            return $this->redirect('/produtos');
        }

        $categorias = $this->categoriaModel->all('cat_nome ASC');

        return $this->view('produtos/edit', [
            'produto' => $produto,
            'categorias' => $categorias
        ]);
    }

    /**
     * Atualiza produto
     */
    public function update($id)
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/produtos');
        }

        $produto = $this->produtoModel->find($id);
        
        if (!$produto) {
            $this->setFlash('error', 'Produto não encontrado!');
            return $this->redirect('/produtos');
        }

        $nome = $this->sanitize($_POST['prod_nome'] ?? '');
        $descricao = $this->sanitize($_POST['prod_descricao'] ?? '');
        $preco = floatval(str_replace(',', '.', $_POST['prod_preco'] ?? '0'));
        $categoria = $_POST['cat_codigo'] ?? null;
        $ativo = isset($_POST['prod_ativo']) ? 1 : 0;
        
        // Validações
        $errors = [];
        if (empty($nome)) $errors[] = 'Nome do produto é obrigatório!';
        if ($preco <= 0) $errors[] = 'Preço deve ser maior que zero!';
        if (empty($categoria)) $errors[] = 'Categoria é obrigatória!';
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            return $this->redirect('/produtos/edit/' . $id);
        }

        // Upload de nova imagem (opcional)
        $imagemNome = $produto['prod_imagem']; // Mantém a imagem atual
        if (!empty($_FILES['prod_imagem']['name'])) {
            try {
                // Remove imagem anterior se existir
                if ($imagemNome && file_exists(__DIR__ . '/../../uploads/' . $imagemNome)) {
                    unlink(__DIR__ . '/../../uploads/' . $imagemNome);
                }
                
                $imagemNome = $this->uploadImage($_FILES['prod_imagem'], 'produtos');
            } catch (\Exception $e) {
                $this->setFlash('error', 'Erro no upload da imagem: ' . $e->getMessage());
                return $this->redirect('/produtos/edit/' . $id);
            }
        }

        try {
            $this->produtoModel->update($id, [
                'prod_nome' => $nome,
                'prod_descricao' => $descricao,
                'prod_preco' => $preco,
                'cat_codigo' => $categoria,
                'prod_ativo' => $ativo,
                'prod_imagem' => $imagemNome
            ]);
            
            $this->setFlash('success', 'Produto atualizado com sucesso!');
            return $this->redirect('/produtos');
            
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erro ao atualizar produto: ' . $e->getMessage());
            return $this->redirect('/produtos/edit/' . $id);
        }
    }

    /**
     * Exclui produto
     */
    public function delete($id)
    {
        $this->requireAuth();
        
        $produto = $this->produtoModel->find($id);
        
        if (!$produto) {
            $this->setFlash('error', 'Produto não encontrado!');
            return $this->redirect('/produtos');
        }

        try {
            // Remove imagem se existir
            if ($produto['prod_imagem'] && file_exists(__DIR__ . '/../../uploads/' . $produto['prod_imagem'])) {
                unlink(__DIR__ . '/../../uploads/' . $produto['prod_imagem']);
            }
            
            $this->produtoModel->delete($id);
            
            $this->setFlash('success', 'Produto excluído com sucesso!');
            return $this->redirect('/produtos');
            
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erro ao excluir produto: ' . $e->getMessage());
            return $this->redirect('/produtos');
        }
    }
}