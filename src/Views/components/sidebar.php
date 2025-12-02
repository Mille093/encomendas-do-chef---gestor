<?php
// O código para obter o caminho atual da URL limpa é crucial para o destaque 'active'.
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove a base do projeto (o que aparece antes da rota real)
// Use o caminho mais limpo '/pedidos/monitorar' para comparação.
$currentPath = str_replace('/encomendas_chef_gestor/public/index.php', '', $currentPath);
$currentPath = str_replace('/public/index.php', '', $currentPath); 
$currentPath = trim($currentPath, '/'); 

// As variáveis antigas como $currentController e $isAjuda baseadas em GET são removidas.
?>

<style>
    .sidebar {
        width: 250px;
        background: linear-gradient(135deg, #C0392B, #E74C3C); 
        color: #fff;
        height: calc(100vh - 60px); 
        position: fixed;
        top: 60px; 
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
        background-color: rgba(255, 255, 255, 0.1); 
    }

    .sidebar a:hover::after {
        width: calc(100% - 40px); 
    }

    /* Ajustado para comparar com o caminho limpo */
    .sidebar a.active {
        background-color: #f4c430; 
        color: #000; 
        font-weight: bold;
    }

    .sidebar a.active:hover {
        background-color: #e0b12a; 
    }

    #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 800; 
    }
</style>

<div id="overlay" class="overlay" onclick="toggleSidebar()"></div>


<div id="sidebar" class="sidebar">
    
  <a href="/encomendas_chef_gestor/public/index.php/pedidos/monitorar" 
    class="<?= strpos($currentPath, 'pedidos/monitorar') !== false ? 'active' : '' ?>">
    Monitorar Pedidos
</a>
    
    <a href="/encomendas_chef_gestor/public/index.php/relatorios/gerar" 
        class="<?= strpos($currentPath, 'relatorios/gerar') !== false ? 'active' : '' ?>">
        Gerar Relatório
    </a>
    
    <a href="/promocoes/gestao" 
        class="<?= strpos($currentPath, 'promocoes/gestao') !== false ? 'active' : '' ?>">
        Gestão de Promoção
    </a>
    
    <a href="/cardapio/gerenciar" 
        class="<?= strpos($currentPath, 'cardapio/gerenciar') !== false ? 'active' : '' ?>">
        Gerenciar Cardápio
    </a>
    
    <a href="/ajuda" 
        class="<?= $currentPath === 'ajuda' ? 'active' : '' ?>">
        Ajuda
    </a>
</div>