<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import InputLabel from '@/Components/Input/InputLabel.vue'
import InputText from '@/Components/Input/InputText.vue'
import InputSelect from '@/Components/Input/InputSelect.vue'
import InputRadio from '@/Components/Input/InputRadio.vue'
import Footer from '@/Layouts/Footer/Footer.vue'

import { reactive } from 'vue'
import { Link } from '@inertiajs/vue3'

const post = reactive({
    code: '',
    services_id: '',
    amount_used: '',
    per_user: '',
    validity: '',
    status_coupon: '',
    percent_value: '',
    value: '',
    percent: '',
    description: ''
})

const services = [
    { value: 1, label: 'Serviço 1' },
    { value: 2, label: 'Serviço 2' },
    { value: 3, label: 'Serviço 3' },
    { value: 4, label: 'Serviço 4' },
    { value: 5, label: 'Serviço 5' },
]

const submitForm = () => {
    console.log(post)
}
</script>

<template>
    <AppLayout title="Exemplos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Pagina de exemplo
            </h2>
        </template>

        <div class="py5">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Componente de demonstração de input inicio -->
                    <div id="content">
                        <div id="title" class="flex p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                            <h2 class="mt-4 text-2xl font-semibold text-gray-900 dark:text-white">
                                Cadastrar Cupon
                            </h2>
                            <div class="py-4 ml-auto">
                                <Link :href="route('dashboard')" >
                                    <button class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-ddteasy focus:ring-offset-2 transition duration-200">
                                        Voltar
                                    </button>
                                </Link>
                            </div>
                        </div>
                        <div id="body" class="mt-4 ml-4 mr-4">
                            <form @submit.prevent="submitForm">
                                <div class="mt-15 mr-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-12">
                                    <div class="sm:col-span-2">
                                        <InputLabel for="Codigo do cupn" value="Codigo do cupon" />
                                        <InputText
                                            v-model="post.code"
                                            type="text"
                                            placeholder="#DESC20"
                                        />
                                    </div>
                                    <div class="sm:col-span-2">
                                        <InputLabel for="Serviço" value="Serviço" />
                                        <InputSelect
                                            v-model="post.services_id"
                                            :options="services"
                                            placeholder="Selecione um serviço"       
                                        />
                                    </div>
                                    <div class="sm:col-span-2">
                                        <InputLabel for="Quantidade de cupons" value="Quantidade de cupons" />
                                        <InputText
                                            v-model="post.amount_used"
                                            type="number"
                                        />
                                    </div>
                                    <div class="sm:col-span-2">
                                        <InputLabel for="Quantidade por usuario" value="Quantidade por usuario" />
                                        <InputText
                                            v-model="post.per_user"
                                            type="number"
                                        />
                                    </div>
                                    <div class="sm:col-span-2">
                                        <InputLabel for="Validade" value="Validade" />
                                        <InputText
                                            v-model="post.validity"
                                            type="date"
                                            placeholder="DD/MM/AAAA"
                                        />
                                    </div>
                                    <div class="sm:col-span-2">
                                        <InputLabel for="Status" value="Status" />
                                        <InputRadio
                                            v-model="post.status_coupon"
                                            :options="[
                                                { value: 'active', label: 'Ativo' },
                                                { value: 'inactive', label: 'Inativo' }
                                            ]"
                                        />
                                    </div>
                                    <div class="sm:col-span-2">
                                        <InputLabel for="Tipo de desconto" value="Tipo de desconto" />
                                        <InputRadio
                                            v-model="post.percent_value"
                                            :options="[
                                                { value: 'percent', label: 'Percentual' },
                                                { value: 'value', label: 'Valor' }
                                            ]"
                                        />
                                    </div>
                                    <div v-if="post.percent_value == 'value'" class="sm:col-span-2">
                                        <InputLabel for="Valor" value="Valor" />
                                        <InputText
                                            v-model="post.value"
                                            type="text"
                                            v-mask-decimal.br="2"
                                        />
                                    </div>
                                    <div v-else-if="post.percent_value == 'percent'" class="sm:col-span-2">
                                        <InputLabel for="Percentual" value="Percentual" />
                                        <InputText
                                            v-model="post.percent"
                                            type="text"
                                            placeholder="20%"
                                            v-mask="'00'"
                                        />
                                    </div>
                                    <div v-else class="sm:col-span-2">

                                    </div>
                                    <div class="sm:col-span-2">
                                        <InputLabel for="Descrição" value="Descrição" />
                                        <InputText
                                            v-model="post.description"
                                            type="text"
                                            placeholder="Descrição do cupon"
                                        />
                                    </div>
                                </div>
                                <div class="h-56 grid grid-cols-3 gap-4 content-center">
                                    <div></div>
                                    <button type="submit" 
                                    class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-ddteasy focus:ring-offset-2 transition duration-200">
                                        Salvar
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div id="footer">
                            <Footer />
                        </div>
                    </div>
                    <!-- Componente de demonstração de input fim -->
                </div>
            </div>
        </div>
        
    </AppLayout>
</template>