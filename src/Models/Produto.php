<?php

namespace Models;

/**
 * Model Produto - Gerencia produtos
 */
class Produto extends Model
{
    protected $table = 'produtos';
    protected $primaryKey = 'prod_id';

    /**
     * Busca produtos ativos
     *
     * @return array
     */
    public function getAtivos()
    {
        return $this->where('prod_ativo', 1);
    }

    /**
     * Busca produtos por categoria
     *
     * @param int $categoriaId
     * @return array
     */
    public function getByCategoria($categoriaId)
    {
        return $this->where('prod_categoria', $categoriaId);
    }

    /**
     * Busca todos os produtos com informações da categoria
     *
     * @return array
     */
    public function getAllWithCategoria()
    {
        $sql = "SELECT p.*, c.cat_nome 
                FROM {$this->table} p 
                LEFT JOIN categorias c ON p.prod_categoria = c.cat_id 
                ORDER BY p.prod_nome";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Busca produto com categoria
     *
     * @param int $id
     * @return array|false
     */
    public function findWithCategoria($id)
    {
        $sql = "SELECT p.*, c.cat_nome 
                FROM {$this->table} p 
                LEFT JOIN categorias c ON p.prod_categoria = c.cat_id 
                WHERE p.prod_id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Busca produtos em promoção
     *
     * @return array
     */
    public function getEmPromocao()
    {
        $sql = "SELECT p.*, c.cat_nome, pr.promo_desconto, pr.promo_data_fim
                FROM {$this->table} p 
                LEFT JOIN categorias c ON p.prod_categoria = c.cat_id
                INNER JOIN promocoes pr ON p.prod_id = pr.promo_produto
                WHERE pr.promo_ativo = 1 
                AND pr.promo_data_inicio <= NOW()
                AND pr.promo_data_fim >= NOW()
                ORDER BY p.prod_nome";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
