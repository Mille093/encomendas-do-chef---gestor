<?php

namespace Models;

/**
 * Model Promocao - Gerencia promoções
 */
class Promocao extends Model
{
    protected $table = 'promocoes';
    protected $primaryKey = 'prm_id';

    /**
     * Busca promoções ativas
     *
     * @return array
     */
    public function ativas()
    {
        $sql = "SELECT * FROM {$this->table} WHERE ativo = 1 AND prm_data_inicio <= CURDATE() AND prm_data_fim >= CURDATE() ORDER BY prm_data_inicio DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Busca promoções por tipo
     *
     * @param string $tipo percentual ou valor
     * @return array
     */
    public function porTipo($tipo)
    {
        $sql = "SELECT * FROM {$this->table} WHERE prm_tipo = ? ORDER BY prm_data_inicio DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$tipo]);
        return $stmt->fetchAll();
    }

    /**
     * Busca promoções vigentes em uma data específica
     *
     * @param string $data Data no formato Y-m-d
     * @return array
     */
    public function vigentesEm($data)
    {
        $sql = "SELECT * FROM {$this->table} WHERE ativo = 1 AND prm_data_inicio <= ? AND prm_data_fim >= ? ORDER BY prm_valor DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$data, $data]);
        return $stmt->fetchAll();
    }

    /**
     * Verifica se uma promoção está ativa
     *
     * @param int $id
     * @return bool
     */
    public function estaAtiva($id)
    {
        $promocao = $this->find($id);
        if (!$promocao) return false;
        
        $hoje = date('Y-m-d');
        return $promocao['ativo'] == 1 
            && $promocao['prm_data_inicio'] <= $hoje 
            && $promocao['prm_data_fim'] >= $hoje;
    }

    /**
     * Aplica uma promoção a um valor
     *
     * @param int $promocaoId
     * @param float $valor
     * @return float Valor com desconto aplicado
     */
    public function aplicarDesconto($promocaoId, $valor)
    {
        $promocao = $this->find($promocaoId);
        if (!$promocao || !$this->estaAtiva($promocaoId)) {
            return $valor;
        }

        if ($promocao['prm_tipo'] === 'percentual') {
            return $valor - ($valor * $promocao['prm_valor'] / 100);
        } elseif ($promocao['prm_tipo'] === 'valor') {
            return max(0, $valor - $promocao['prm_valor']);
        }

        return $valor;
    }

    /**
     * Busca promoções que estão expirando em X dias
     *
     * @param int $dias Número de dias
     * @return array
     */
    public function expirandoEm($dias = 7)
    {
        $dataLimite = date('Y-m-d', strtotime("+{$dias} days"));
        $sql = "SELECT * FROM {$this->table} WHERE ativo = 1 AND prm_data_fim <= ? AND prm_data_fim >= CURDATE() ORDER BY prm_data_fim ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$dataLimite]);
        return $stmt->fetchAll();
    }
}