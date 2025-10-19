<?php

namespace Models;

/**
 * Model Gestor - Gerencia usuários administradores
 */
class Gestor extends Model
{
    protected $table = 'gestores';
    protected $primaryKey = 'gst_id';

    /**
     * Busca gestor por email
     *
     * @param string $email
     * @return array|false
     */
    public function findByEmail($email)
    {
        return $this->first('gst_email', $email);
    }

    /**
     * Verifica se o email já existe
     *
     * @param string $email
     * @param int|null $excludeId
     * @return bool
     */
    public function emailExists($email, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE gst_email = ? AND gst_id != ?");
            $stmt->execute([$email, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE gst_email = ?");
            $stmt->execute([$email]);
        }
        
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }

    /**
     * Cria um novo gestor com senha hash
     *
     * @param string $nome
     * @param string $email
     * @param string $senha
     * @return string ID do gestor criado
     */
    public function createGestor($nome, $email, $senha)
    {
        $data = [
            'gst_nome' => $nome,
            'gst_email' => $email,
            'gst_senha' => password_hash($senha, PASSWORD_DEFAULT),
            'gst_criado_em' => date('Y-m-d H:i:s')
        ];
        
        return $this->create($data);
    }

    /**
     * Verifica credenciais de login
     *
     * @param string $email
     * @param string $senha
     * @return array|false
     */
    public function verificarCredenciais($email, $senha)
    {
        $gestor = $this->findByEmail($email);
        
        if ($gestor && password_verify($senha, $gestor['gst_senha'])) {
            return $gestor;
        }
        
        return false;
    }
}
