<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações Gerais
    |--------------------------------------------------------------------------
    |
    | Configurações gerais para o Laravel Combo Stack.
    |
    */
    
    'company_name' => env('APP_NAME', 'Sh4rck3'), // Nome da empresa
    
    /*
    |--------------------------------------------------------------------------
    | Configurações do Jetstream
    |--------------------------------------------------------------------------
    |
    | Personalize as configurações do Jetstream.
    |
    */
    
    'jetstream' => [
        'teams' => false,
        'api' => true,
        'profile_photos' => true,
        'dark_mode' => true,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Papéis e Permissões
    |--------------------------------------------------------------------------
    |
    | Define os papéis e permissões iniciais que serão criados
    | automaticamente no sistema.
    |
    */
    
    'roles' => [
        'admin' => [
            'name' => 'Administrador',
            'permissions' => ['gerenciar-usuarios', 'gerenciar-conteudo', 'acessar-relatorios'],
        ],
        'manager' => [
            'name' => 'Gerente',
            'permissions' => ['gerenciar-conteudo', 'acessar-relatorios'],
        ],
        'user' => [
            'name' => 'Usuário',
            'permissions' => ['acessar-conteudo'],
        ],
    ],

     /*
    |--------------------------------------------------------------------------
    | Criação de Usuários Padrão
    |--------------------------------------------------------------------------
    |
    | Define se usuários padrão devem ser criados para cada papel
    | e suas configurações.
    |
    */
    
    'create_default_users' => true,
    
    'default_users' => [
        'admin' => [
            'name' => 'SquadDev',
            'email' => 'squadev@squaddev.com',
            'password' => 'squaddev123',
        ],
        'manager' => [
            'name' => 'Gerente',
            'email' => 'gerente@squaddev.com',
            'password' => 'squaddev123',
        ],
        'user' => [
            'name' => 'Usuário',
            'email' => 'usuario@squaddev.com',
            'password' => 'squaddev123',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Configurações de Rota
    |--------------------------------------------------------------------------
    |
    | Configurações relacionadas às rotas criadas pelo pacote.
    |
    */
    
    'routes' => [
        'prefix' => '', // Prefixo para as rotas web
        'api_prefix' => 'api', // Prefixo para as rotas API
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Configurações de Visual
    |--------------------------------------------------------------------------
    |
    | Configurações para a aparência das páginas.
    |
    */
    
    'ui' => [
        'primary_color' => '#4F46E5', // Cor principal do tema
        'logo_path' => null, // Caminho para um logo personalizado
    ],
];