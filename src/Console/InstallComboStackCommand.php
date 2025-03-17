<?php

namespace Sh4rck3\LaravelComboStack\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;


class InstallComboStackCommand extends Command
{
    protected $signature = 'combo-stack:install';
    protected $description = 'Instala e configura Jetstream e Spatie Permission automaticamente.';


    public function handle()
    {
        $this->info('Instalando Combo Stack...');
        
        // Instala componentes principais
        $this->installJetstream();
        $this->installSpatiePermission();

        // Substituir o model User pelo stub e fazer backup do antigo
        $this->replaceUserModelWithStub();

        // Configurar o arquivo de configuração
        $this->publishConfig();

        

        
        // Configurar papéis e permissões
        $this->setupRolesAndPermissions();

        // Configurar middleware Inertia
        $this->setupInertiaMiddleware();

        // Configurar helper de permissões para Vue
        $this->setupPermissionsHelper();

        // Instalar dependências NPM e configurar bibliotecas
        $this->installNpmDependencies();
        
        // Personaliza a aplicação
        $this->publishWelcomeFile();
        $this->setupPageController();
        $this->updateWebRoutes();
        
        // Compila assets
        $this->compileAssets();
        
        $this->info('Combo Stack instalado com sucesso!');
    }

    protected function replaceUserModelWithStub()
    {
        $userModelPath = app_path('Models/User.php');
        $backupModelPath = app_path('Models/User_backup_' . date('Ymd_His') . '.php');
        $stubPath = __DIR__.'/../../resources/stubs/Models/User.php';

        if (File::exists($userModelPath)) {
            // Criar backup do arquivo original
            File::copy($userModelPath, $backupModelPath);
        }

        // Copiar o stub para o local do model User
        File::copy($stubPath, $userModelPath);
    }

    protected function installNpmDependencies()
    {
        $this->info('Instalando bibliotecas NPM necessárias...');

        // Copiar package.json modificado
        $sourcePackageJson = __DIR__.'/../../resources/stubs/package.json';
        $destPackageJson = base_path('package.json');
        
        if (File::exists($destPackageJson)) {
            // Fazer backup do package.json original
            $backupPath = $destPackageJson . '.backup-' . date('Y-m-d_H-i-s');
            File::copy($destPackageJson, $backupPath);
            $this->info('Backup do package.json original criado em: ' . $backupPath);
        }
        
        // Copiar package.json com as dependências necessárias
        File::copy($sourcePackageJson, $destPackageJson);
        $this->info('Arquivo package.json atualizado com sucesso.');
        
        // Copiar a pasta Store
        $sourceStore = __DIR__.'/../../resources/stubs/js/Store';
        $destStore = resource_path('js/Store');
        
        if (!File::isDirectory($destStore)) {
            File::makeDirectory($destStore, 0755, true);
        }
        
        // Copiar o arquivo MyStore.js
        File::copy($sourceStore . '/MyStore.js', $destStore . '/MyStore.js');
        $this->info('Store Vuex configurada com sucesso.');

        // Copiar o arquivo tailwind.config.js
        $sourceTailwindConfig = __DIR__.'/../../resources/stubs/tailwind.config.js';
        $destTailwindConfig = base_path('tailwind.config.js');
        
        if (File::exists($destTailwindConfig)) {
            // Fazer backup do tailwind.config.js original
            $backupPath = $destTailwindConfig . '.backup-' . date('Y-m-d_H-i-s');
            File::copy($destTailwindConfig, $backupPath);
            $this->info('Backup do tailwind.config.js original criado em: ' . $backupPath);
        }
        
        // Copiar tailwind.config.js
        File::copy($sourceTailwindConfig, $destTailwindConfig);
        $this->info('Arquivo tailwind.config.js copiado com sucesso.');

        // Copiar a pasta Icons
        $sourceIcons = __DIR__.'/../../resources/stubs/js/Icons';
        $destIcons = resource_path('js/Icons');
        
        if (!File::isDirectory($destIcons)) {
            File::makeDirectory($destIcons, 0755, true);
        }
        
        // Copiar arquivos de ícones SVG
        $iconFiles = File::allFiles($sourceIcons);
        foreach ($iconFiles as $file) {
            $destFile = $destIcons . '/' . $file->getFilename();
            if (File::exists($destFile)) {
                // Fazer backup do arquivo existente
                $backupPath = $destFile . '.backup-' . date('Y-m-d_H-i-s');
                File::copy($destFile, $backupPath);
                $this->info('Backup do ' . $file->getFilename() . ' original criado em: ' . $backupPath);
            }
            File::copy($file->getPathname(), $destFile);
        }
        $this->info('Ícones SVG copiados com sucesso.');

        // Copiar a pasta Layouts e suas subpastas
        $sourceLayouts = __DIR__.'/../../resources/stubs/js/Layouts';
        $destLayouts = resource_path('js/Layouts');
        
        if (!File::isDirectory($destLayouts)) {
            File::makeDirectory($destLayouts, 0755, true);
        }

         // Copiar arquivos de layout e subpastas
        $layoutFiles = File::allFiles($sourceLayouts);
        foreach ($layoutFiles as $file) {
            $relativePath = str_replace($sourceLayouts, '', $file->getPathname());
            $destFile = $destLayouts . $relativePath;
            $destDir = dirname($destFile);
            
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true);
            }
            
            if (File::exists($destFile)) {
                // Fazer backup do arquivo existente
                $backupPath = $destFile . '.backup-' . date('Y-m-d_H-i-s');
                File::copy($destFile, $backupPath);
                $this->info('Backup do ' . $file->getFilename() . ' original criado em: ' . $backupPath);
            }
            File::copy($file->getPathname(), $destFile);
        }
        $this->info('Layouts copiados com sucesso.');

        // Copiar o arquivo app.blade.php
        $sourceAppBlade = __DIR__.'/../../resources/stubs/js/views/app.blade.php';
        $destAppBlade = resource_path('views/app.blade.php');
        
        if (File::exists($destAppBlade)) {
            // Fazer backup do app.blade.php original
            $backupPath = $destAppBlade . '.backup-' . date('Y-m-d_H-i-s');
            File::copy($destAppBlade, $backupPath);
            $this->info('Backup do app.blade.php original criado em: ' . $backupPath);
        }
        
        // Copiar app.blade.php
        File::copy($sourceAppBlade, $destAppBlade);
        $this->info('Arquivo app.blade.php copiado com sucesso.');


        // Perguntar ao usuário se deseja instalar as dependências NPM
        if ($this->confirm('Deseja instalar as dependências NPM agora?', true)) {
            $this->info('Instalando dependências NPM. Isso pode levar alguns minutos...');
            
            $process = Process::fromShellCommandline('npm install', base_path());
            $process->setTimeout(null);
            
            // Mostrar saída em tempo real
            $process->run(function ($type, $buffer) {
                $this->output->write($buffer);
            });
            
            if ($process->isSuccessful()) {
                $this->info('Dependências NPM instaladas com sucesso!');
            } else {
                $this->warn('Houve um problema ao instalar as dependências NPM.');
                $this->warn('Você pode precisar executar manualmente: npm install');
            }
        } else {
            $this->info('Pule a instalação das dependências NPM.');
            $this->info('Você pode instalá-las manualmente executando: npm install');
        }
    }

    protected function setupPermissionsHelper()
    {
        $this->info('Configurando helpers de verificação de permissões para o frontend...');
        
        // Estrutura de arquivos a copiar (origem => destino)
        $files = [
            // Plugin de permissões
            'js/Plugins/Permissions.js' => resource_path('js/Plugins/Permissions.js'),
            
            // Componente de exemplo
            'js/Components/PermissionExample.vue' => resource_path('js/Components/PermissionExample.vue'),
            
            // Dashboard modificado (opcional)
            'js/Pages/Dashboard.vue' => resource_path('js/Pages/Dashboard.vue'),

            //Exemplo de pagina de input
            'js/Pages/Example.vue' => resource_path('js/Pages/Example.vue'),
            
            // App.js modificado
            'js/app.js' => resource_path('js/app.js'),

            // Componente DarkMode
            'js/Components/DarkMode/DarkMode.vue' => resource_path('js/Components/DarkMode/DarkMode.vue'),
        ];
        
        // Processar cada arquivo
        foreach ($files as $source => $destination) {
            // Caminho completo do arquivo de origem
            $sourcePath = __DIR__.'/../../resources/stubs/' . $source;
            
            // Verificar se o arquivo de origem existe
            if (!File::exists($sourcePath)) {
                $this->warn("Arquivo stub não encontrado: {$source}");
                continue;
            }
            
            // Garantir que o diretório de destino existe
            $destinationDir = dirname($destination);
            if (!File::isDirectory($destinationDir)) {
                File::makeDirectory($destinationDir, 0755, true);
            }
            
            // Se o arquivo já existe, fazer backup
            if (File::exists($destination)) {
                $backupPath = $destination . '.backup-' . date('Y-m-d_H-i-s');
                File::copy($destination, $backupPath);
                $this->info("Backup criado: {$backupPath}");
            }
            
            // Copiar o arquivo
            File::copy($sourcePath, $destination);
            $this->info("Arquivo copiado: {$destination}");
        }
        
        $this->info('Helpers de permissões e componentes de exemplo configurados com sucesso!');
    }

    protected function setupInertiaMiddleware()
    {
        // Verifica se o Spatie Permission está instalado
        if (!class_exists('\Spatie\Permission\Models\Role')) {
            $this->error('O pacote spatie/laravel-permission não está instalado!');
            $this->info('Execute: composer require spatie/laravel-permission');
            $this->info('Em seguida, execute este comando novamente.');
            return false;
        }
        
        $this->info('Configurando middleware Inertia com suporte a papéis e permissões...');
        
        // Caminho do arquivo de origem no seu pacote
        $sourcePath = __DIR__.'/../../resources/stubs/Http/Middleware/HandleInertiaRequests.php';
        
        // Caminho de destino no projeto Laravel
        $destinationPath = app_path('Http/Middleware/HandleInertiaRequests.php');

        // Se o arquivo já existe, faz backup automaticamente
        if (File::exists($destinationPath)) {
            // Criar backup com timestamp para evitar sobrescrever backups anteriores
            $backupPath = $destinationPath . '.backup-' . date('Y-m-d_H-i-s');
            File::copy($destinationPath, $backupPath);
            $this->info('Backup do HandleInertiaRequests.php original criado em: ' . $backupPath);
        }

        // Copiar o arquivo sem perguntar
        File::copy($sourcePath, $destinationPath);
        $this->info('Middleware HandleInertiaRequests atualizado com suporte a papéis e permissões.');
    }
    
    protected function installJetstream()
    {
        $config = config('combo-stack');
        
        // Instala Jetstream com opções da configuração
        $this->call('jetstream:install', [
            'stack' => 'inertia',
            '--teams' => $config['jetstream']['teams'] ?? true,
            '--ssr' => $config['jetstream']['ssr'] ?? true,
            '--dark' => $config['jetstream']['dark_mode'] ?? true,
        ]);
    }
    
    protected function installSpatiePermission()
    {
        $config = config('combo-stack');

        $this->info('Instalando Spatie Permission...');
        $this->call('vendor:publish', ['--provider' => 'Spatie\Permission\PermissionServiceProvider']);
        $this->call('migrate');

        // Configura papéis e permissões do Spatie baseados na configuração
        // if (isset($config['roles'])) {
        //     $this->setupRolesAndPermissions($config['roles']);
        // }
    }

    protected function publishWelcomeFile()
    {
        // Verifica se o diretório já existe, indicando instalação prévia do Inertia+Vue
        $destinationDir = resource_path('js/Pages');
        if (!File::isDirectory($destinationDir)) {
            $this->error('O diretório js/Pages não foi encontrado!');
            $this->error('Faltam os pacotes necessários. Favor revisar!');
            $this->info('Execute primeiro: php artisan jetstream:install inertia --ssr --dark');
            $this->info('Em seguida, execute: npm install && npm run dev');
            $this->info('Depois disso, execute este comando novamente.');
            
            $this->warn('Instalação cancelada. Configure o Jetstream com Inertia primeiro.');
            return false;
        }

        // Caminho do arquivo de origem no seu pacote
        $sourcePath = __DIR__.'/../../resources/stubs/js/Pages/Welcome.vue';
        
        // Verifica se o arquivo stub existe
        if (!File::exists($sourcePath)) {
            $this->error("O arquivo de template Welcome.vue não foi encontrado no pacote!");
            return false;
        }
        
        // Caminho de destino no projeto Laravel
        $destinationPath = resource_path('js/Pages/Welcome.vue');

        // Se o arquivo já existe, faz backup automaticamente
        if (File::exists($destinationPath)) {
            // Criar backup com timestamp para evitar sobrescrever backups anteriores
            $backupPath = $destinationPath . '.backup-' . date('Y-m-d_H-i-s');
            File::copy($destinationPath, $backupPath);
            $this->info('Backup do Welcome.vue original criado em: ' . $backupPath);
        }

        // Copiar o arquivo sem perguntar
        File::copy($sourcePath, $destinationPath);
        $this->info('Arquivo Welcome.vue personalizado publicado com sucesso.');

        
        return true;
    }

    protected function setupPageController()
    {
        $this->info('Configurando PageController...');
        
        // Criar diretório se não existir
        $controllerDir = app_path('Http/Controllers/Web');
        if (!File::isDirectory($controllerDir)) {
            File::makeDirectory($controllerDir, 0755, true);
        }
        
        // Copiar arquivo do PageController do pacote para o projeto
        $sourcePath = __DIR__.'/../../resources/stubs/Controllers/PageController.php';
        $destinationPath = app_path('Http/Controllers/Web/PageController.php');
        
        if (File::exists($destinationPath)) {
            // Criar backup
            $backupPath = $destinationPath . '.backup-' . date('Y-m-d_H-i-s');
            File::copy($destinationPath, $backupPath);
            $this->info('Backup do PageController.php criado em: ' . $backupPath);
        }
        
        File::copy($sourcePath, $destinationPath);
        $this->info('PageController.php criado com sucesso.');
    }
    
    protected function updateWebRoutes()
    {
        $this->info('Atualizando rotas web...');
        
        $routesPath = base_path('routes/web.php');
        $originalContent = File::get($routesPath);
        
        // Fazer backup do arquivo original
        $backupPath = $routesPath . '.backup-' . date('Y-m-d_H-i-s');
        File::put($backupPath, $originalContent);
        $this->info('Backup do arquivo routes/web.php criado em: ' . $backupPath);
        
        // Conteúdo para substituir no routes/web.php
        $newContent = <<<'EOT'
                        <?php

                        use Illuminate\Support\Facades\Route;
                        use App\Http\Controllers\Web\PageController;

                        /*
                        |--------------------------------------------------------------------------
                        | Web Routes
                        |--------------------------------------------------------------------------
                        |
                        | Here is where you can register web routes for your application. These
                        | routes are loaded by the RouteServiceProvider and all of them will
                        | be assigned to the "web" middleware group. Make something great!
                        |
                        */

                        Route::get('/', [PageController::class, 'welcome'])->name('welcome');

                        Route::middleware([
                            'auth:sanctum',
                            config('jetstream.auth_session'),
                            'verified',
                        ])->group(function () {
                            Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
                            Route::get('/example', [PageController::class, 'example'])->name('example');
                        });
                        EOT;

        File::put($routesPath, $newContent);
        $this->info('Arquivo routes/web.php atualizado com sucesso.');
    }
    
    protected function compileAssets()
    {
        $this->info('Compilando os assets do frontend...');
        
        $process = Process::fromShellCommandline('npm run build', base_path());
        $process->setTimeout(null);
        
        $process->run(function ($type, $buffer) {
            $this->output->write($buffer);
        });
        
        if ($process->isSuccessful()) {
            $this->info('Assets compilados com sucesso!');
        } else {
            $this->warn('Houve um problema na compilação dos assets.');
            $this->warn('Você pode precisar executar manualmente: npm run build');
        }
    }

    protected function setupRolesAndPermissions()
    {
        $this->info('Configurando papéis, permissões e usuários...');
    
        // Cria o diretório de seeders se não existir
        if (!File::exists(database_path('seeders'))) {
            File::makeDirectory(database_path('seeders'), 0755, true);
        }
        
        // Copia todos os seeders necessários
        $seeders = [
            'RolesAndPermissionsSeeder.php',
            'UserSeeder.php',
            'ComboStackDatabaseSeeder.php',
        ];
        
        foreach ($seeders as $seeder) {
            $sourcePath = __DIR__.'/../../resources/stubs/database/seeders/' . $seeder;
            $destinationPath = database_path('seeders/' . $seeder);
            File::copy($sourcePath, $destinationPath);
            $this->info('Arquivo ' . $seeder . ' copiado com sucesso.');
        }
        
        // Executa o seeder principal
        $this->call('db:seed', [
            '--class' => 'Database\\Seeders\\ComboStackDatabaseSeeder'
        ]);
    }

    protected function publishConfig()
    {
        $this->info('Publicando arquivo de configuração...');
        $this->call('vendor:publish', ['--tag' => 'combo-stack-config']);
    }

}
