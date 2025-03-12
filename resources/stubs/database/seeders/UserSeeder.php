<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obter configurações do arquivo combo-stack.php
        $userConfig = config('combo-stack.default_users', []);
        $rolesConfig = config('combo-stack.roles', []);

        // Criar usuários padrão para cada papel, se configurado
        foreach ($rolesConfig as $roleKey => $roleData) {
            $roleName = $roleData['name'] ?? $roleKey;
            
            // Verifica se existe uma configuração específica para este usuário
            $userData = $userConfig[$roleKey] ?? null;
            
            if ($userData || config('combo-stack.create_default_users', true)) {
                // Se não houver configuração específica, usa valores padrão
                $email = $userData['email'] ?? strtolower($roleName) . '@example.com';
                $name = $userData['name'] ?? ucfirst($roleName);
                $password = $userData['password'] ?? 'password';
                
                // Verifica se o usuário já existe
                $user = User::where('email', $email)->first();
                
                if (!$user) {
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'email_verified_at' => now(),
                    ]);
                    
                    $this->command->info("Usuário '{$name}' ({$email}) criado com senha '{$password}'.");
                } else {
                    $this->command->info("Usuário '{$name}' ({$email}) já existe.");
                }
                
                // Atribui o papel ao usuário
                $role = Role::findByName($roleName);
                $user->assignRole($role);
                
                $this->command->info("Papel '{$roleName}' atribuído ao usuário '{$name}'.");
            }
        }
    }
}