<?php

namespace Controllers;

use Models\Pedido;
use Models\Produto;
use Models\Categoria;

/**
 * DashboardController - Gerencia o dashboard principal
 */
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->requireAuth();
    }

    /**
     * Exibe o dashboard principal
     */
    public function index()
    {
        $pedidoModel = new Pedido();
        $produtoModel = new Produto();
        $categoriaModel = new Categoria();

        // Estatísticas
        $stats = [
            'total_pedidos' => $pedidoModel->count(),
            'pedidos_pendentes' => $pedidoModel->countByStatus('pendente'),
            'pedidos_hoje' => $pedidoModel->getPedidosHoje(),
            'total_produtos' => $produtoModel->count(),
            'total_categorias' => $categoriaModel->count(),
            'total_vendas' => $pedidoModel->getTotalVendas()
        ];

        // Pedidos recentes
        $pedidosRecentes = $pedidoModel->getAllWithCliente();
        // Limita aos 6 mais recentes
        $pedidosRecentes = array_slice($pedidosRecentes, 0, 6);

        $this->view('dashboard/index', [
            'stats' => $stats,
            'pedidos' => $pedidosRecentes,
            'user' => $this->getAuthUser()
        ]);
    }
}
