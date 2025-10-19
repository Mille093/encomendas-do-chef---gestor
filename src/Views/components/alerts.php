<?php
/**
 * Componente: Alerts - Mensagens de sucesso/erro
 */

// Mensagens de sucesso
if (isset($_SESSION['flash']['success'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['flash']['success']) ?>
        <?php unset($_SESSION['flash']['success']); ?>
    </div>
<?php endif;

// Mensagens de erro
if (isset($_SESSION['flash']['error'])): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($_SESSION['flash']['error']) ?>
        <?php unset($_SESSION['flash']['error']); ?>
    </div>
<?php endif;
