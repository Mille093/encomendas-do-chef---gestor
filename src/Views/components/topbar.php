<?php
$userName = $_SESSION['gestor_nome'] ?? 'Usuário';
?>

<style>
    .topbar {
        background-color: #f4c430; 
        color: #fff;
        width: 100%;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
        height: 80px;
        box-sizing: border-box;
    }

    .topbar-left {
        display: flex;
        align-items: center;
    }

    .page-title {
        margin: 0;
        font-size: 1.5em;
        font-weight: bold;
        color: #fff;
        text-transform: uppercase; 
    }

    .topbar-right {
        display: flex;
        align-items: center;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 15px; 
    }

    .user-info span {
        font-size: 1em;
        color: #fff;
        font-weight: 500; 
    }

    .btn-logout {
        background-color: #d32f2f; 
        color: #fff;
        padding: 8px 15px; 
        border: none;
        border-radius: 5px; 
        font-size: 0.9em;
        font-weight: bold;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.3s;
        text-transform: uppercase; 
    }

    .btn-logout:hover {
        background-color: #b71c1c; 
    }
</style>

<header class="topbar">
    <div class="topbar-left">
         <a href="<?= $config['base_url'] ?>/">
            <img src="public/assets/chef.png" alt="Logo Encomendas do Chef" style="max-width: 60px; height:60px auto; vertical-align: middle;">
        </a>
        <h1 class="page-title"><?= $pageTitle ?? 'Encomendas do Chef - Gestor' ?></h1>
    </div>
    
    <div class="topbar-right">
        <div class="user-info">
            <span>Olá, <?= htmlspecialchars($userName) ?></span>
            <a href="<?= $config['base_url'] ?>/logout" class="btn-logout">Sair</a>
        </div>
    </div>
</header>