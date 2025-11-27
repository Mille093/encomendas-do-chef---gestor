<?php

/**
 * Redirecionamento para o sistema principal
 * Este arquivo redireciona para o front controller correto
 */

// Inicia a sessão
session_start();

// Carrega configurações
$config = require __DIR__ . '/config/app.php';
$baseUrl = $config['base_url'];

// Verifica se é uma requisição para Ajuda ou Termos (páginas públicas)
$controller = $_GET['controller'] ?? '';
$action = $_GET['action'] ?? 'index';

if ($controller === 'Ajuda' && $action === 'index') {
    $controllerFile = 'src/Controllers/AjudaController.php';
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $ajuda = new AjudaController();
        $ajuda->index();
        exit;
    } else {
        die("Erro: Controller não encontrado em <code>$controllerFile</code>");
    }
}

if ($controller === 'Termos' && $action === 'index') {
    $controllerFile = 'src/Controllers/TermosController.php'; 
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $termos = new TermosController();
        $termos->index();
        exit;
    } else {
        die("Erro: Controller não encontrado em <code>$controllerFile</code>");
    }
}

// Se houver sessão ativa, redireciona para o dashboard
if (isset($_SESSION['gestor_id'])) {
    header("Location: {$baseUrl}/public/app.php");
    exit;
}

// Se não houver sessão, redireciona para o login
header("Location: {$baseUrl}/public/app.php/login");
exit;