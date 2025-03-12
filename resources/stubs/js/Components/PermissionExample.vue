<template>
  <div class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
      <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          Demonstração de Permissões
      </h3>
      
      <div class="mt-4 space-y-2">
          <div v-if="$hasRole('admin')" class="p-2 bg-green-100 dark:bg-green-800 rounded text-gray-900 dark:text-gray-100">
              Você tem o papel de Administrador
          </div>
          <div v-else class="p-2 bg-red-100 dark:bg-red-800 rounded text-gray-900 dark:text-gray-100">
              Você não é um Administrador
          </div>
          
          <div v-if="$can('gerenciar-usuarios')" class="p-2 bg-green-100 dark:bg-green-800 rounded text-gray-900 dark:text-gray-100">
              Você pode gerenciar usuários
          </div>
          <div v-else class="p-2 bg-red-100 dark:bg-red-800 rounded text-gray-900 dark:text-gray-100">
              Você não pode gerenciar usuários
          </div>
          
          <div v-if="$canAny(['gerenciar-usuarios', 'visualizar-usuarios'])" class="p-2 bg-green-100 dark:bg-green-800 rounded text-gray-900 dark:text-gray-100">
              Você pode gerenciar ou visualizar usuários
          </div>
          
          <div v-if="$hasAnyRole(['admin', 'manager'])" class="p-2 bg-green-100 dark:bg-green-800 rounded text-gray-900 dark:text-gray-100">
              Você é um Administrador ou Gerente
          </div>
      </div>
  </div>
</template>

<script setup>
// Componente sem estado, apenas demonstração dos helpers
</script>