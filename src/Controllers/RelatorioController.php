<?php

namespace Controllers;

class RelatorioController extends Controller
{
    public function __construct()
    {
        // Não precisa de model específico, usa queries diretas
    }

    /**
     * Relatório de vendas
     */
    public function vendas()
    {
        $this->requireAuth();
        
        // Receber parâmetros de data
        $de = $_GET['de'] ?? date('Y-m-01'); // Primeiro dia do mês atual
        $ate = $_GET['ate'] ?? date('Y-m-d'); // Data atual
        
        $pdo = \Core\Database::getInstance()->getConnection();
        
        // Estatísticas gerais do período
        $stmt = $pdo->prepare(
            "SELECT 
                COUNT(*) as total_pedidos, 
                COALESCE(SUM(ped_valor_total), 0) as faturamento_total,
                AVG(ped_valor_total) as ticket_medio
             FROM pedidos 
             WHERE DATE(ped_data_elaboracao) BETWEEN :de AND :ate 
             AND ped_status != 'cancelado'"
        );
        $stmt->execute(['de' => $de, 'ate' => $ate]);
        $estatisticas = $stmt->fetch();
        
        // Top produtos mais vendidos
        $stmt = $pdo->prepare(
            "SELECT 
                pr.prod_nome, 
                SUM(ip.itp_quantidade_comprada) as quantidade_vendida,
                SUM(ip.itp_quantidade_comprada * ip.itp_preco_unitario) as valor_total
             FROM itens_pedido ip
             JOIN produtos pr ON pr.prod_codigo = ip.prod_codigo
             JOIN pedidos p ON p.ped_numero = ip.ped_numero
             WHERE DATE(p.ped_data_elaboracao) BETWEEN :de AND :ate
             AND p.ped_status != 'cancelado'
             GROUP BY pr.prod_codigo, pr.prod_nome
             ORDER BY quantidade_vendida DESC
             LIMIT 10"
        );
        $stmt->execute(['de' => $de, 'ate' => $ate]);
        $topProdutos = $stmt->fetchAll();
        
        // Vendas por dia (últimos 30 dias para gráfico)
        $stmt = $pdo->prepare(
            "SELECT 
                DATE(ped_data_elaboracao) as data,
                COUNT(*) as pedidos,
                SUM(ped_valor_total) as faturamento
             FROM pedidos 
             WHERE DATE(ped_data_elaboracao) BETWEEN :de AND :ate
             AND ped_status != 'cancelado'
             GROUP BY DATE(ped_data_elaboracao)
             ORDER BY DATE(ped_data_elaboracao) ASC"
        );
        $stmt->execute(['de' => $de, 'ate' => $ate]);
        $vendasDiarias = $stmt->fetchAll();
        
        // Pedidos por status no período
        $stmt = $pdo->prepare(
            "SELECT 
                ped_status,
                COUNT(*) as quantidade,
                SUM(ped_valor_total) as valor_total
             FROM pedidos 
             WHERE DATE(ped_data_elaboracao) BETWEEN :de AND :ate
             GROUP BY ped_status
             ORDER BY quantidade DESC"
        );
        $stmt->execute(['de' => $de, 'ate' => $ate]);
        $pedidosPorStatus = $stmt->fetchAll();
        
        // Categorias mais vendidas
        $stmt = $pdo->prepare(
            "SELECT 
                c.cat_nome,
                COUNT(DISTINCT ip.ped_numero) as pedidos_com_categoria,
                SUM(ip.itp_quantidade_comprada) as quantidade_vendida,
                SUM(ip.itp_quantidade_comprada * ip.itp_preco_unitario) as valor_total
             FROM itens_pedido ip
             JOIN produtos pr ON pr.prod_codigo = ip.prod_codigo
             JOIN categorias c ON c.cat_codigo = pr.cat_codigo
             JOIN pedidos p ON p.ped_numero = ip.ped_numero
             WHERE DATE(p.ped_data_elaboracao) BETWEEN :de AND :ate
             AND p.ped_status != 'cancelado'
             GROUP BY c.cat_codigo, c.cat_nome
             ORDER BY valor_total DESC"
        );
        $stmt->execute(['de' => $de, 'ate' => $ate]);
        $categorias = $stmt->fetchAll();

        return $this->view('relatorios/vendas', [
            'de' => $de,
            'ate' => $ate,
            'estatisticas' => $estatisticas,
            'topProdutos' => $topProdutos,
            'vendasDiarias' => $vendasDiarias,
            'pedidosPorStatus' => $pedidosPorStatus,
            'categorias' => $categorias
        ]);
    }

    /**
     * Relatório de produtos
     */
    public function produtos()
    {
        $this->requireAuth();
        
        $pdo = \Core\Database::getInstance()->getConnection();
        
        // Produtos com estatísticas de venda
        $stmt = $pdo->query(
            "SELECT 
                pr.*,
                c.cat_nome,
                COALESCE(stats.total_vendido, 0) as total_vendido,
                COALESCE(stats.quantidade_vendida, 0) as quantidade_vendida,
                COALESCE(stats.ultimo_pedido, 'Nunca') as ultimo_pedido
             FROM produtos pr
             LEFT JOIN categorias c ON c.cat_codigo = pr.cat_codigo
             LEFT JOIN (
                 SELECT 
                     ip.prod_codigo,
                     SUM(ip.itp_quantidade_comprada * ip.itp_preco_unitario) as total_vendido,
                     SUM(ip.itp_quantidade_comprada) as quantidade_vendida,
                     MAX(p.ped_data_elaboracao) as ultimo_pedido
                 FROM itens_pedido ip
                 JOIN pedidos p ON p.ped_numero = ip.ped_numero
                 WHERE p.ped_status != 'cancelado'
                 GROUP BY ip.prod_codigo
             ) stats ON stats.prod_codigo = pr.prod_codigo
             ORDER BY pr.prod_nome"
        );
        
        $produtos = $stmt->fetchAll();

        return $this->view('relatorios/produtos', [
            'produtos' => $produtos
        ]);
    }

    /**
     * Relatório de clientes
     */
    public function clientes()
    {
        $this->requireAuth();
        
        $pdo = \Core\Database::getInstance()->getConnection();
        
        // Clientes com estatísticas de pedidos
        $stmt = $pdo->query(
            "SELECT 
                c.*,
                COALESCE(stats.total_pedidos, 0) as total_pedidos,
                COALESCE(stats.total_gasto, 0) as total_gasto,
                COALESCE(stats.ultimo_pedido, 'Nunca') as ultimo_pedido,
                COALESCE(stats.ticket_medio, 0) as ticket_medio
             FROM clientes c
             LEFT JOIN (
                 SELECT 
                     p.cli_codigo,
                     COUNT(*) as total_pedidos,
                     SUM(p.ped_valor_total) as total_gasto,
                     MAX(p.ped_data_elaboracao) as ultimo_pedido,
                     AVG(p.ped_valor_total) as ticket_medio
                 FROM pedidos p
                 WHERE p.ped_status != 'cancelado'
                 GROUP BY p.cli_codigo
             ) stats ON stats.cli_codigo = c.cli_codigo
             ORDER BY stats.total_gasto DESC NULLS LAST"
        );
        
        $clientes = $stmt->fetchAll();

        return $this->view('relatorios/clientes', [
            'clientes' => $clientes
        ]);
    }

    /**
     * Exportar relatório em CSV
     */
    public function exportCsv($tipo)
    {
        $this->requireAuth();
        
        $de = $_GET['de'] ?? date('Y-m-01');
        $ate = $_GET['ate'] ?? date('Y-m-d');
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="relatorio_' . $tipo . '_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        $pdo = \Core\Database::getInstance()->getConnection();
        
        switch ($tipo) {
            case 'vendas':
                // Cabeçalho CSV
                fputcsv($output, ['Data', 'Pedidos', 'Faturamento']);
                
                // Dados
                $stmt = $pdo->prepare(
                    "SELECT 
                        DATE(ped_data_elaboracao) as data,
                        COUNT(*) as pedidos,
                        SUM(ped_valor_total) as faturamento
                     FROM pedidos 
                     WHERE DATE(ped_data_elaboracao) BETWEEN :de AND :ate
                     AND ped_status != 'cancelado'
                     GROUP BY DATE(ped_data_elaboracao)
                     ORDER BY DATE(ped_data_elaboracao) ASC"
                );
                $stmt->execute(['de' => $de, 'ate' => $ate]);
                
                while ($row = $stmt->fetch()) {
                    fputcsv($output, [
                        $row['data'],
                        $row['pedidos'],
                        number_format($row['faturamento'], 2, ',', '.')
                    ]);
                }
                break;
                
            case 'produtos':
                fputcsv($output, ['Produto', 'Categoria', 'Quantidade Vendida', 'Valor Total']);
                
                $stmt = $pdo->prepare(
                    "SELECT 
                        pr.prod_nome,
                        c.cat_nome,
                        SUM(ip.itp_quantidade_comprada) as quantidade,
                        SUM(ip.itp_quantidade_comprada * ip.itp_preco_unitario) as total
                     FROM itens_pedido ip
                     JOIN produtos pr ON pr.prod_codigo = ip.prod_codigo
                     LEFT JOIN categorias c ON c.cat_codigo = pr.cat_codigo
                     JOIN pedidos p ON p.ped_numero = ip.ped_numero
                     WHERE DATE(p.ped_data_elaboracao) BETWEEN :de AND :ate
                     AND p.ped_status != 'cancelado'
                     GROUP BY pr.prod_codigo
                     ORDER BY total DESC"
                );
                $stmt->execute(['de' => $de, 'ate' => $ate]);
                
                while ($row = $stmt->fetch()) {
                    fputcsv($output, [
                        $row['prod_nome'],
                        $row['cat_nome'] ?? 'Sem categoria',
                        $row['quantidade'],
                        number_format($row['total'], 2, ',', '.')
                    ]);
                }
                break;
        }
        
        fclose($output);
        exit;
    }
}