<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PermissionExample from '@/Components/PermissionExample.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <!-- <Welcome /> -->
                    <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">Bem-vindo ao Laravel Combo Stack!</h2>
                    <p class="text-gray-600 dark:text-gray-400">Seu aplicativo está configurado e pronto para uso.</p>
                </div>

                <!-- Componente de demonstração de permissões -->
                <PermissionExample />
            </div>
        </div>
    </AppLayout>
</template>