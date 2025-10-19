<?php

namespace Models;

/**
 * Model Categoria - Gerencia categorias de produtos
 */
class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'cat_id';

    /**
     * Busca categorias ativas
     *
     * @return array
     */
    public function getAtivas()
    {
        return $this->where('cat_ativo', 1);
    }

    /**
     * Verifica se o nome da categoria já existe
     *
     * @param string $nome
     * @param int|null $excludeId
     * @return bool
     */
    public function nomeExists($nome, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE cat_nome = ? AND cat_id != ?");
            $stmt->execute([$nome, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE cat_nome = ?");
            $stmt->execute([$nome]);
        }
        
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }

    /**
     * Conta produtos por categoria
     *
     * @param int $categoriaId
     * @return int
     */
    public function countProdutos($categoriaId)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM produtos WHERE prod_categoria = ?");
        $stmt->execute([$categoriaId]);
        $result = $stmt->fetch();
        return (int) $result['total'];
    }
}
