//configuração com BD

<?php
// src/config.php
return [
    'db' => [
        'host' => '127.0.0.1',
        'dbname' => 'encomendas_chef_db',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4'
    ],
    'base_url' => '/encomendas-chef-gestor/public' // ajuste conforme seu apache/nginx
];
