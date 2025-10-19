<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Encomendas do Chef' ?> - Área do Gestor</title>
    <link rel="stylesheet" href="/encomendas_chef_gestor/public/css/styles.css">
</head>
<body>
    <div class="app-wrapper">
        <?php require __DIR__ . '/../components/sidebar.php'; ?>
        
        <div class="main-container">
            <?php require __DIR__ . '/../components/topbar.php'; ?>
            
            <main class="main">
                <?php require __DIR__ . '/../components/alerts.php'; ?>
                
                <?= $content ?? '' ?>
            </main>
        </div>
    </div>

    <script src="/encomendas_chef_gestor/public/js/main.js"></script>
    <?= $scripts ?? '' ?>
</body>
</html>
