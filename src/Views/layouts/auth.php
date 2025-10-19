<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login' ?> - Encomendas do Chef</title>
    <link rel="stylesheet" href="/encomendas_chef_gestor/public/css/styles.css">
</head>
<body class="auth-body">
    <div class="auth-wrapper">
        <?= $content ?? '' ?>
    </div>

    <script src="/encomendas_chef_gestor/public/js/main.js"></script>
</body>
</html>
