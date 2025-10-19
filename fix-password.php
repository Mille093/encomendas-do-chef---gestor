<?php
echo "<h1>Atualizar Senha do Admin</h1>";

try {
    // Conectar ao banco
    $config = require __DIR__ . '/config/database.php';
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Gerar hash da senha
    $senhaHash = password_hash('123456', PASSWORD_DEFAULT);
    
    echo "<strong>Nova senha hash:</strong><br>";
    echo "<code>$senhaHash</code><br><br>";
    
    // Atualizar no banco
    $stmt = $pdo->prepare("UPDATE gestores SET gst_senha = ? WHERE gst_email = 'admin@encomendaschef.local'");
    $resultado = $stmt->execute([$senhaHash]);
    
    if ($resultado) {
        echo "✅ <strong>Senha atualizada com sucesso!</strong><br><br>";
        
        // Verificar se funcionou
        echo "<strong>Teste de verificação:</strong><br>";
        $stmt = $pdo->prepare("SELECT gst_senha FROM gestores WHERE gst_email = 'admin@encomendaschef.local'");
        $stmt->execute();
        $gestor = $stmt->fetch();
        
        if (password_verify('123456', $gestor['gst_senha'])) {
            echo "✅ Verificação OK - a senha '123456' funciona agora!<br>";
        } else {
            echo "❌ Ainda há problema com a verificação da senha<br>";
        }
        
    } else {
        echo "❌ Erro ao atualizar a senha<br>";
    }
    
    echo "<br><hr><br>";
    echo "<strong>Credenciais para login:</strong><br>";
    echo "Email: <code>admin@encomendaschef.local</code><br>";
    echo "Senha: <code>123456</code><br>";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage();
}
?>