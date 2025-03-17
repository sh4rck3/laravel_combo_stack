<template>
    <div class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
        <div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
              Demonstração de Permissões
          </h3>
          
          <div class="mt-4 space-y-2">
              <div v-if="$hasRole('Administrador')" class="p-2 bg-green-100 dark:bg-green-800 rounded text-gray-900 dark:text-gray-100">
                  Você tem o papel de Administrador
                  <div>
                      outra forma de checar a permissão
                      <div v-if="pageRole.includes('Administrador')" class="p-2 bg-green-100 dark:bg-green-800 rounded text-gray-900 dark:text-gray-100">
                          Você pode gerenciar usuários: pageRole.includes('Administrador')
                          <p>
                              import { usePage  } from '@inertiajs/vue3'<br>
  
                              const page = usePage()<br>
                              const pagePermission = computed(() => page.props.auth.user.permissions)<br>
                              const pageRole = computed(() => page.props.auth.user.roles)<br>
                          </p>
                      </div>
                  </div>
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
              
              <div v-if="$hasAnyRole(['Administrador', 'Gerente'])" class="p-2 bg-green-100 dark:bg-green-800 rounded text-gray-900 dark:text-gray-100">
                  Você é um Administrador ou Gerente
              </div>
          </div>
        </div>
        <div>
          <button @click="swalButton" class="mt-4 mr-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
              Clique aqui para testar o SweetAlert
          </button>
          <button @click="toastButton" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
              Clique aqui para testar o Toast
          </button>
        </div>
    </div>
  </template>
  
  <script setup>
  import { usePage  } from '@inertiajs/vue3'
  import { computed, onMounted, inject } from 'vue'
  
  const page = usePage();
  const pagePermission = computed(() => page.props.auth.user.permissions)
  const pageRole = computed(() => page.props.auth.user.roles)
  
  const swal = inject('$swal')
  const toast = inject('$toast')
  
  const swalButton = () =>{
      swal.fire({
          title: 'Você clicou no botão',
          text: 'Você clicou no botão',
          icon: 'success',
          confirmButtonText: 'Fechar'
      })
  }
  
  const toastButton = () =>{
      toast.success('Pagamento aceito com sucesso!', {
              position: "top-right",
              timeout: 10000,
              closeOnClick: true,
              pauseOnFocusLoss: true,
              pauseOnHover: true,
              draggable: true,
              draggablePercent: 0.6,
              showCloseButtonOnHover: false,
              hideProgressBar: true,
              closeButton: "button",
              icon: true,
              rtl: false
          })
  }
  
  onMounted(() => {
      //console.log(pagePermission);
      //console.log(pageRole.includes('administrador'))
      //console.log(pageRole);
      //console.log(page.props.auth.user)
  
      
  })
  </script>