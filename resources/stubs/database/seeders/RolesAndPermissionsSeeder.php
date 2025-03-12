<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar cache das permissões - importante ao executar seeds
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Obter configurações do arquivo combo-stack.php
        $rolesConfig = config('combo-stack.roles', []);

        // Criar permissões únicas
        $allPermissions = [];
        foreach ($rolesConfig as $roleData) {
            if (isset($roleData['permissions'])) {
                $allPermissions = array_merge($allPermissions, $roleData['permissions']);
            }
        }

        // Remover duplicatas
        $allPermissions = array_unique($allPermissions);

        // Criar permissões no banco
        foreach ($allPermissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $this->command->info('Permissões criadas com sucesso!');

        // Criar papéis e atribuir permissões
        foreach ($rolesConfig as $roleKey => $roleData) {
            $roleName = $roleData['name'] ?? $roleKey;
            $role = Role::findOrCreate($roleName);

            if (isset($roleData['permissions'])) {
                $role->syncPermissions($roleData['permissions']);
            }

            $this->command->info("Papel '{$roleName}' criado com suas permissões!");
        }
    }
}