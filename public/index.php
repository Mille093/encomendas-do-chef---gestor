<?php

/**
 * Front Controller - Ponto de entrada único da aplicação
 * Todas as requisições passam por este arquivo
 */

// Inicia a sessão
session_start();

// Define o timezone
date_default_timezone_set('America/Sao_Paulo');

// Carrega configurações
$config = require __DIR__ . '/../config/app.php';

// Configura exibição de erros baseado no ambiente
if ($config['environment'] === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Carrega o Autoloader
require_once __DIR__ . '/../src/Core/Autoloader.php';
Core\Autoloader::register();

// Cria o roteador
$router = new Core\Router();

// ==================== ROTAS DE AUTENTICAÇÃO ====================
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// ==================== ROTAS DO DASHBOARD ====================
$router->get('/', 'DashboardController@index');
$router->get('/dashboard', 'DashboardController@index');

// ==================== ROTAS DE CATEGORIAS ====================
$router->get('/categorias', 'CategoriaController@index');
$router->get('/categorias/add', 'CategoriaController@create');
$router->post('/categorias/add', 'CategoriaController@store');
$router->get('/categorias/edit/{id}', 'CategoriaController@edit');
$router->post('/categorias/edit/{id}', 'CategoriaController@update');
$router->post('/categorias/delete/{id}', 'CategoriaController@delete');

// ==================== ROTAS DE PRODUTOS ====================
$router->get('/produtos', 'ProdutoController@index');
$router->get('/produtos/add', 'ProdutoController@create');
$router->post('/produtos/add', 'ProdutoController@store');
$router->get('/produtos/edit/{id}', 'ProdutoController@edit');
$router->post('/produtos/edit/{id}', 'ProdutoController@update');
$router->post('/produtos/delete/{id}', 'ProdutoController@delete');

// ==================== ROTAS DE PEDIDOS ====================
$router->get('/pedidos', 'PedidoController@index');
$router->get('/pedidos/view/{id}', 'PedidoController@show');
$router->post('/pedidos/update-status/{id}', 'PedidoController@updateStatus');

// ==================== ROTAS DE API ====================
$router->get('/api/pedidos-count', 'PedidoController@getEstats');

// ==================== ROTAS DE PROMOÇÕES ====================
$router->get('/promocoes', 'PromocaoController@index');
$router->get('/promocoes/add', 'PromocaoController@create');
$router->post('/promocoes/add', 'PromocaoController@store');
$router->get('/promocoes/edit/{id}', 'PromocaoController@edit');
$router->post('/promocoes/edit/{id}', 'PromocaoController@update');

// ==================== ROTAS DE RELATÓRIOS ====================
$router->get('/relatorios/vendas', 'RelatorioController@vendas');

// Despacha a requisição
try {
    $router->dispatch();
} catch (Exception $e) {
    // Em produção, registrar o erro e mostrar página amigável
    if ($config['environment'] === 'development') {
        echo "<h1>Erro na Aplicação</h1>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        error_log($e->getMessage());
        http_response_code(500);
        echo "<h1>Erro Interno do Servidor</h1>";
        echo "<p>Desculpe, algo deu errado. Tente novamente mais tarde.</p>";
    }
}
