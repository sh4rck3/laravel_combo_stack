<?php

namespace Sh4rck3\LaravelComboStack\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;


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

        // Configurar o arquivo de configuração
        $this->publishConfig();

        // Substituir o model User pelo stub e fazer backup do antigo
        $this->replaceUserModelWithStub();
        
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
        // Caminho do arquivo de origem no seu pacote
        $sourcePath = resource_path('stubs/Models/User.php');
        
        // Verifica se o arquivo stub existe
        if (!File::exists($sourcePath)) {
            $this->error("O arquivo de template User.php não foi encontrado no pacote!");
            return false;
        }

        // Caminho de destino no projeto Laravel
        $destinationPath = app_path('Models/User.php');

        // Se o arquivo já existe, faz backup automaticamente
        if (File::exists($destinationPath)) {
            // Criar backup com timestamp para evitar sobrescrever backups anteriores
            $backupPath = $destinationPath . '.backup-' . date('Y-m-d_H-i-s');
            File::copy($destinationPath, $backupPath);
            $this->info('Backup do User.php original criado em: ' . $backupPath);
        }

        // Copiar o arquivo sem perguntar
        File::copy($sourcePath, $destinationPath);
        $this->info('Arquivo User.php personalizado publicado com sucesso.');

        return true;
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
            
            // App.js modificado
            'js/app.js' => resource_path('js/app.js'),
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
        if (isset($config['roles'])) {
            $this->setupRolesAndPermissions($config['roles']);
        }
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
