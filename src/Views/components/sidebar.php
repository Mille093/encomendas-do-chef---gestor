<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>

<style>
    .sidebar {
        width: 250px;
        background: linear-gradient(135deg, #C0392B, #E74C3C); 
        color: #fff;
        height: calc(100vh - 60px); /* Altura total menos a altura do topbar (60px) */
        position: fixed;
        top: 60px; /* Posiciona abaixo do topbar */
        left: 0;
        padding: 25px 0;
        box-sizing: border-box;
        overflow-y: auto; 
        z-index: 900; 
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2); 
        border-right: 1px solid rgba(255, 255, 255, 0.1); 
    }

    .sidebar-header {
        padding: 15px 20px;
        text-align: center;
        background: rgba(0, 0, 0, 0.2); 
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 20px;
    }

    .sidebar-header h2 {
        margin: 0;
        font-size: 1.4em;
        color: #FFD700; 
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3); 
        font-weight: 600;
    }

    .sidebar a {
        display: block; 
        padding: 12px 20px;
        color: #fff;
        text-decoration: none;
        font-size: 1.5em;
        font-family: Arial, Helvetica, sans-serif;
        transition: all 0.3s ease; 
        position: relative; 
    }

    .sidebar a::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 20px;
        width: 0;
        height: 2px;
        background-color: #FFD700; 
        transition: width 0.3s ease;
    }

    .sidebar a:hover {
        background-color: rgba(255, 255, 255, 0.1); /* Fundo claro no hover */
    }

    .sidebar a:hover::after {
        width: calc(100% - 40px); /* Linha se expande sob o texto */
    }

    .sidebar a.active {
        background-color: #f4c430; /* Amarelo para item ativo */
        color: #000; /* Texto preto no item ativo */
        font-weight: bold;
    }

    .sidebar a.active:hover {
        background-color: #e0b12a; /* Tom mais claro no hover do ativo */
    }

    #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 800; /* Fica abaixo da sidebar, mas acima do conteúdo */
    }
</style>

<div id="overlay" class="overlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
    
    <a href="index.php?controller=Perfil&action=index" class="<?= strpos($currentPath, '/perfil') !== false ? 'active' : '' ?>">Monitorar Pedidos</a>
    <a href="index.php?controller=Carrinho&action=visualizar" class="<?= strpos($currentPath, '/carrinho') !== false ? 'active' : '' ?>">Gerar Relatório</a>
    <a href="index.php?controller=Pedido&action=listar" class="<?= strpos($currentPath, '/pedido') !== false ? 'active' : '' ?>">Gestão de Promoção</a>
    <a href="index.php?controller=Configuracoes&action=index" class="<?= strpos($currentPath, '/configuracoes') !== false ? 'active' : '' ?>">Gerenciar Cardápio</a>
    <a href="index.php?controller=Ajuda&action=index" class="<?= strpos($currentPath, '/ajuda') !== false ? 'active' : '' ?>">Ajuda</a>
</div>