<?php



// Pega a URI requisitada
$uri = $_SERVER['REQUEST_URI'] ?? '/';

// Remove a base do projeto da URI
$projectBase = '/encomendas_chef_gestor';
if (strpos($uri, $projectBase) === 0) {
    $uri = substr($uri, strlen($projectBase));
}

// Remove query string se existir
$queryString = '';
if (strpos($uri, '?') !== false) {
    list($uri, $queryString) = explode('?', $uri, 2);
    $queryString = '?' . $queryString;
}

// Remove barras extras
$uri = '/' . trim($uri, '/');

// Se estiver vazio, vai para home
if ($uri === '/' || $uri === '') {
    $uri = '/dashboard';
}

// Redireciona para o Front Controller real
header("Location: {$projectBase}/public/app.php{$uri}{$queryString}");
exit;
