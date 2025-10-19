<?php

namespace Models;

/**
 * Model Pedido - Gerencia pedidos
 */
class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'ped_numero';

    /**
     * Busca todos os pedidos com informações do cliente
     *
     * @return array
     */
    public function getAllWithCliente()
    {
        $sql = "SELECT p.*, c.cli_nome, c.cli_telefone 
                FROM {$this->table} p
                INNER JOIN clientes c ON p.cli_codigo = c.cli_codigo
                ORDER BY p.ped_data_elaboracao DESC";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Busca pedidos por status
     *
     * @param string $status
     * @return array
     */
    public function getByStatus($status)
    {
        $sql = "SELECT p.*, c.cli_nome, c.cli_telefone 
                FROM {$this->table} p
                INNER JOIN clientes c ON p.cli_codigo = c.cli_codigo
                WHERE p.ped_status = ?
                ORDER BY p.ped_data_elaboracao DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    /**
     * Busca pedido completo com itens
     *
     * @param int $id
     * @return array|false
     */
    public function findComplete($id)
    {
        // Busca pedido com cliente
        $sql = "SELECT p.*, c.cli_nome, c.cli_telefone, c.cli_endereco, c.cli_email
                FROM {$this->table} p
                INNER JOIN clientes c ON p.cli_codigo = c.cli_codigo
                WHERE p.ped_numero = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $pedido = $stmt->fetch();

        if (!$pedido) {
            return false;
        }

        // Busca itens do pedido
        $sql = "SELECT pi.*, prod.prod_nome
                FROM pedido_itens pi
                INNER JOIN produtos prod ON pi.prod_id = prod.prod_id
                WHERE pi.ped_numero = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $pedido['itens'] = $stmt->fetchAll();

        return $pedido;
    }

    /**
     * Atualiza status do pedido
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus($id, $status)
    {
        return $this->update($id, ['ped_status' => $status]);
    }

    /**
     * Conta pedidos por status
     *
     * @param string $status
     * @return int
     */
    public function countByStatus($status)
    {
        return $this->count('ped_status', $status);
    }

    /**
     * Busca pedidos do dia
     *
     * @return int
     */
    public function getPedidosHoje()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM {$this->table} WHERE DATE(ped_data_elaboracao) = CURDATE()");
        $result = $stmt->fetch();
        return (int) $result['total'];
    }

    /**
     * Retorna o total de pedidos
     *
     * @return int
     */
    public function getTotalPedidos()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM {$this->table}");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Retorna o total de pedidos pendentes
     *
     * @return int
     */
    public function getPedidosPendentes()
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->table} WHERE ped_status = ?");
        $stmt->execute(['pendente']);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Calcula total de vendas (excluindo cancelados)
     * Pode filtrar por período específico
     *
     * @param string|null $dataInicio
     * @param string|null $dataFim
     * @return float
     */
    public function getTotalVendas($dataInicio = null, $dataFim = null)
    {
        $sql = "SELECT COALESCE(SUM(ped_valor_total), 0) as total FROM {$this->table} WHERE ped_status != 'cancelado'";
        $params = [];

        if ($dataInicio && $dataFim) {
            $sql .= " AND DATE(ped_data_elaboracao) BETWEEN ? AND ?";
            $params = [$dataInicio, $dataFim];
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return (float) $result['total'];
    }

    /**
     * Retorna estatísticas completas para dashboard
     *
     * @return array
     */
    public function getEstatisticas()
    {
        return [
            'total_pedidos' => $this->getTotalPedidos(),
            'pendentes' => $this->getPedidosPendentes(),
            'total_vendas' => $this->getTotalVendas()
        ];
    }
}
