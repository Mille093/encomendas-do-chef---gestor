<?php
// routes

/**
 * * Este arquivo registra todas as rotas (URLS) da aplicação
 * e as mapeia para um Controller@Método.
 * */

// --- Rotas Principais ---
// Home Page / Dashboard
$router->get('/', 'HomeController@index');

// Central de Ajuda (AjudaController@index)
$router->get('/ajuda', 'AjudaController@index');


// --- Rotas de Autenticação ---
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@processLogin'); // Rota para processar o formulário
$router->get('/logout', 'AuthController@logout');


// --- Rotas de Pedidos (Geral) ---

// Listagem de Pedidos com Filtros (View: views/pedidos/index.php)
$router->get('/pedidos', 'PedidoController@index');

// Rota para ver detalhes de um pedido específico
$router->get('/pedidos/view/{id}', 'PedidoController@show');


// --- Rotas de Monitoramento e Ações ---

// ✅ NOVA ROTA: Tela de Monitoramento de Pedidos em Andamento
// (Controller: PedidoController@monitorarPedidos)
$router->get('/pedidos/monitorar', 'PedidoController@monitorarPedidos'); 


// Rota POST para atualizar o status de um pedido
$router->post('/pedidos/update-status/{id}', 'PedidoController@updateStatus');

// Rota para cancelar um pedido
$router->post('/pedidos/cancel/{id}', 'PedidoController@cancel');


// --- Rotas de API/Estatísticas ---
// Exemplo: para buscar dados para o Dashboard
$router->get('/api/estats', 'PedidoController@getEstats');


// --- Rotas de Produtos ---
// Exemplo:
// $router->get('/produtos', 'ProdutoController@index');
// $router->get('/produtos/criar', 'ProdutoController@create');
// ...

// Rotas de Usuários, Configurações, etc.
// ...