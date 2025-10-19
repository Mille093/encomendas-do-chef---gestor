<?php
require_once __DIR__ . '/../src/helpers.php';
session_destroy();
header('Location: /encomendas_chef_gestor/login');
exit;
