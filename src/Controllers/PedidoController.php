<?php

namespace Controllers;

use Models\Pedido;

class PedidoController extends Controller
{
    private $pedidoModel;

    public function __construct()
    {
        $this->pedidoModel = new Pedido();
    }

    /**
     * Lista todos os pedidos com filtro por status
     */
    public function index()
    {
        // ... (Código existente)
        $this->requireAuth();
        
        $filter = $_GET['status'] ?? '';
        
        // Query com JOIN para buscar nome do cliente
        $pdo = \Core\Database::getInstance()->getConnection();
        $sql = "SELECT p.*, c.cli_nome FROM pedidos p JOIN clientes c ON p.cli_codigo = c.cli_codigo";
        
        if ($filter) {
            $sql .= " WHERE p.ped_status = :status";
            $stmt = $pdo->prepare($sql . " ORDER BY p.ped_data_elaboracao DESC");
            $stmt->execute(['status' => $filter]);
        } else {
            $stmt = $pdo->query($sql . " ORDER BY p.ped_data_elaboracao DESC");
        }
        
        $pedidos = $stmt->fetchAll();
        
        return $this->view('pedidos/index', [
            'pedidos' => $pedidos,
            'filtroAtual' => $filter
        ]);
    }

    /**
     * Exibe detalhes de um pedido
     */
    public function show($id)
    {
        // ... (Código existente)
        $this->requireAuth();
        
        // Buscar pedido com dados do cliente
        $pdo = \Core\Database::getInstance()->getConnection();
        $stmt = $pdo->prepare(
            "SELECT p.*, c.cli_nome, c.cli_email, c.cli_telefone, c.cli_endereco 
             FROM pedidos p 
             JOIN clientes c ON p.cli_codigo = c.cli_codigo 
             WHERE p.ped_numero = ?"
        );
        $stmt->execute([$id]);
        $pedido = $stmt->fetch();
        
        if (!$pedido) {
            $this->setFlash('error', 'Pedido não encontrado!');
            return $this->redirect('/pedidos');
        }

        // Buscar itens do pedido
        $stmt = $pdo->prepare(
            "SELECT ip.*, pr.prod_nome, pr.prod_imagem 
             FROM itens_pedido ip 
             JOIN produtos pr ON ip.prod_codigo = pr.prod_codigo 
             WHERE ip.ped_numero = ?
             ORDER BY pr.prod_nome"
        );
        $stmt->execute([$id]);
        $itens = $stmt->fetchAll();

        return $this->view('pedidos/show', [
            'pedido' => $pedido,
            'itens' => $itens
        ]);
    }

    /**
     * Exibe a tela de monitoramento de pedidos (Em Andamento).
     * Essa é a tela que criamos com o CSS estilizado.
     */
    public function monitorarPedidos()
    {
        $this->requireAuth();
        
        $pdo = \Core\Database::getInstance()->getConnection();

        // 1. Consulta SQL para buscar pedidos em andamento (pendente ou em_preparacao) E seus itens
        $stmt = $pdo->prepare("
            SELECT 
                p.ped_numero, 
                c.cli_nome, 
                p.ped_status, 
                p.ped_data_elaboracao,
                p.ped_observacao,
                ip.ip_quantidade,
                pr.prod_nome
            FROM pedidos p
            JOIN clientes c ON p.cli_codigo = c.cli_codigo
            JOIN itens_pedido ip ON p.ped_numero = ip.ped_numero
            JOIN produtos pr ON ip.prod_codigo = pr.prod_codigo
            WHERE p.ped_status IN ('pendente', 'em_preparacao')
            ORDER BY p.ped_data_elaboracao ASC
        ");
        
        $stmt->execute();
        $dadosCru = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // 2. Agrupa os itens por Pedido (Estruturando os dados para a View)
        $pedidosAgrupados = [];
        foreach ($dadosCru as $linha) {
            $ped_numero = $linha['ped_numero'];

            // Se o pedido ainda não foi adicionado ao array agrupado, inicializa
            if (!isset($pedidosAgrupados[$ped_numero])) {
                $pedidosAgrupados[$ped_numero] = [
                    'numero' => $ped_numero,
                    'cliente' => $linha['cli_nome'],
                    'status' => $linha['ped_status'],
                    'data' => $linha['ped_data_elaboracao'],
                    'observacao' => $linha['ped_observacao'],
                    'itens' => []
                ];
            }
            
            // Adiciona o item à lista de itens do pedido
            $pedidosAgrupados[$ped_numero]['itens'][] = [
                'quantidade' => $linha['ip_quantidade'],
                'nome' => $linha['prod_nome']
            ];
        }

        // 3. Carrega a View, passando os dados estruturados
        return $this->view('pedidos/monitorar', [
            'pedidos' => $pedidosAgrupados
        ]);
    }

    /**
     * Atualiza status do pedido
     */
    public function updateStatus($id)
    {
        // ... (Código existente)
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/pedidos');
        }

        $pedido = $this->pedidoModel->find($id);
        
        if (!$pedido) {
            $this->setFlash('error', 'Pedido não encontrado!');
            return $this->redirect('/pedidos');
        }

        $novoStatus = $_POST['ped_status'] ?? '';
        
        // Validar status permitidos
        $statusPermitidos = ['pendente', 'em_preparacao', 'pronto', 'entregue', 'cancelado'];
        
        if (!in_array($novoStatus, $statusPermitidos)) {
            $this->setFlash('error', 'Status inválido!');
            return $this->redirect('/pedidos/view/' . $id);
        }

        try {
            // Atualizar status
            $this->pedidoModel->update($id, [
                'ped_status' => $novoStatus
            ]);
            
            // Se foi marcado como pronto, definir data de finalização
            if ($novoStatus === 'pronto') {
                $this->pedidoModel->update($id, [
                    'ped_data_finalizacao' => date('Y-m-d H:i:s')
                ]);
            }
            
            $this->setFlash('success', 'Status do pedido atualizado com sucesso!');
            return $this->redirect('/pedidos/view/' . $id);
            
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erro ao atualizar status: ' . $e->getMessage());
            return $this->redirect('/pedidos/view/' . $id);
        }
    }

    /**
     * Marca pedido como cancelado
     */
    public function cancel($id)
    {
        // ... (Código existente)
        $this->requireAuth();
        
        $pedido = $this->pedidoModel->find($id);
        
        if (!$pedido) {
            $this->setFlash('error', 'Pedido não encontrado!');
            return $this->redirect('/pedidos');
        }

        // Não permitir cancelar pedidos já finalizados
        if (in_array($pedido['ped_status'], ['entregue', 'cancelado'])) {
            $this->setFlash('error', 'Não é possível cancelar um pedido já finalizado!');
            return $this->redirect('/pedidos/view/' . $id);
        }

        try {
            $this->pedidoModel->update($id, [
                'ped_status' => 'cancelado'
            ]);
            
            $this->setFlash('success', 'Pedido cancelado com sucesso!');
            return $this->redirect('/pedidos');
            
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erro ao cancelar pedido: ' . $e->getMessage());
            return $this->redirect('/pedidos/view/' . $id);
        }
    }

    /**
     * API: Busca estatísticas dos pedidos (compatível com pedidos_count.php)
     */
    public function getEstats()
    {
        // ... (Código existente)
        $this->requireAuth();
        
        // Buscar estatísticas através do Model
        $estatisticas = $this->pedidoModel->getEstatisticas();
        
        return $this->json([
            'ok' => true,
            'total_pedidos' => $estatisticas['total_pedidos'],
            'pendentes' => $estatisticas['pendentes'], 
            'total_vendas' => $estatisticas['total_vendas']
        ]);
    }
}