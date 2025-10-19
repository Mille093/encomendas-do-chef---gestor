// funções utilitárias

<?php
// src/helpers.php
session_start();

function is_logged_in() {
    return isset($_SESSION['gestor_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: /encomendas_chef_gestor/login');
        exit;
    }
}

function flash_set($k, $v) {
    $_SESSION['flash'][$k] = $v;
}
function flash_get($k) {
    if (isset($_SESSION['flash'][$k])) {
        $v = $_SESSION['flash'][$k];
        unset($_SESSION['flash'][$k]);
        return $v;
    }
    return null;
}
