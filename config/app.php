<?php

/**
 * Configurações da Aplicação
 */
return [
    // Nome da aplicação
    'app_name' => 'Encomendas do Chef',
    
    // URL base da aplicação
    'base_url' => '/encomendas_chef_gestor',
    
    // Ambiente: development, production
    'environment' => 'development',
    
    // Timezone
    'timezone' => 'America/Sao_Paulo',
    
    // Sessão
    'session_name' => 'encomendas_chef_session',
    'session_lifetime' => 7200, // 2 horas
    
    // Upload
    'upload_path' => __DIR__ . '/../uploads/',
    'upload_max_size' => 5242880, // 5MB
    'upload_allowed_types' => ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'],
    
    // Exibir erros (apenas em development)
    'display_errors' => true,
];
