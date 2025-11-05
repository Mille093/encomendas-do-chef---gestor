<?php


ini_set('display_errors', 1);
error_reporting(E_ALL);

$controller = $_GET['controller'] ?? '';
$action = $_GET['action'] ?? 'index';


if ($controller === 'Ajuda' && $action === 'index') {
    $controllerFile = 'src/Controllers/AjudaController.php'; // CAMINHO CORRETO

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


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Acesso Restrito</title>
    <style>
        body { font-family: Arial; text-align: center; padding: 50px; background: #f4f4f4; }
        .box { background: white; padding: 40px; border-radius: 10px; display: inline-block; box-shadow: 0 4px 20px rgba(0,0,0,0.1); max-width: 500px; }
        h1 { color: #C0392B; }
        a { color: #FFD700; font-weight: bold; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Acesso Direto Bloqueado</h1>
        <p>Esta é uma página interna de ajuda.</p>
        <p><a href="index.php?controller=Ajuda&action=index">Abrir Central de Ajuda</a></p> <!-- CORRIGIDO O TYPO: controllers → controller -->
    </div>
</body>
</html>