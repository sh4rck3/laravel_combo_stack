<script setup>
import { Link, usePage, router } from "@inertiajs/vue3"
import ApplicationMark from '@/Components/ApplicationMark.vue'
import { computed, ref, onMounted, defineEmits, watch } from "vue"
import Icon from "@/Icons/Icons.vue"
import AOS from "aos"
import { store } from '@/Pages/Store/Mysotre'

//permissions page
const page = usePage();
const pagePermission = computed(() => page.props.auth.user.permissions);
const pageRole = computed(() => page.props.auth.user.roles);

//start functions menu page

const isMenuOpen = (menuId) => {
    return store.getters.isMenuOpen(menuId);
};
const activeRoute = computed(() => store.getters.activeRoute);

const handleItemClick = (route, menuId) => {
    store.dispatch("setActiveRoute", route);
};

const handleOtherClick = (route, menuId) => {
    toggleMenu(menuId);
    store.dispatch("setActiveRoute", route);
};

const toggleMenu = (menuId) => {
    store.dispatch("toggleMenu", menuId);
};

const isActiveRoute = (route) => {
    return route === activeRoute.value;
};

// State to control sidebar expansion
const isSidebarExpanded = ref(true);

// Define emit
const emit = defineEmits(['toggle-sidebar']);

const toggleSidebar = () => {
    isSidebarExpanded.value = !isSidebarExpanded.value;
    emit('toggle-sidebar', isSidebarExpanded.value);
}

// Watch for route changes and update the active route
watch(() => page.url, (newUrl) => {
    console.log(newUrl)
    if (newUrl.includes('/dashboard')) {
        handleOtherClick(route('dashboard'), 'menuDashboard')
    } 
}, { immediate: true })

//end functions menu page

onMounted(() => {
    console.log('page: ', page.url)
    AOS.init({
        duration: 1200,
        throttleDelay: 99,
    });
});
</script>
<style>
.slide-fade-enter-active {
    transition: all 0.5s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}

.sidebar-expanded {
    width: 16rem; /* 64px */
}

.sidebar-collapsed {
    width: 4rem; /* 16px */
}

.main-expanded {
    margin-left: 16rem; /* 64px */
}

.main-collapsed {
    margin-left: 4rem; /* 16px */
}
</style>

<template>
    <aside
        :class="[
            'fixed top-0 left-0 z-40 h-screen transition-transform bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700',
            isSidebarExpanded ? 'sidebar-expanded' : 'sidebar-collapsed'
        ]"
        aria-label="Sidenav"
        id="drawer-navigation"
    >
        <!-- Logo Fixa no Topo -->
        <div class="flex items-center justify-center h-16">
            <Link :href="route('dashboard')" >
                <ApplicationMark :class="[ isSidebarExpanded ? 'block h-9 w-auto' : 'block h-4 w-auto' ]" class="" />
            </Link>
            <p v-if="isSidebarExpanded" class="dark:text-gray-300 ml-4">Nome da Empresa</p>
            <button @click="toggleSidebar" class="">
                <Icon :name="isSidebarExpanded ? 'chevron-left' : 'chevron-right'" class="w-6 h-6 dark:text-gray-300" />
            </button>
        </div>

        <!-- Menu de Navegação -->
        <div class="overflow-y-auto py-5 px-3 h-full bg-white dark:bg-gray-800">
            <ul class="space-y-2">
                <li>
                    <Link
                        :href="route('dashboard')"
                        @click="handleOtherClick(route('dashboard'), 'menuDashboard')"
                        :class="[
                            'flex items-center p-2 w-full text-base font-medium rounded-lg transition duration-75 group',
                            isActiveRoute(route('dashboard'))
                                ? 'dark:text-gray-300 dark:bg-gray-600 text-gray-200 bg-slate-400'
                                : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700',
                        ]"
                    >
                        <Icon name="chartpie" class="w-6 h-6" />
                        <span v-if="isSidebarExpanded" class="ml-3">Painel</span>
                    </Link>
                </li>
                <li>
                    <Link
                        :href="route('example')"
                        @click="handleOtherClick(route('example'), 'menuExample')"
                        :class="[
                            'flex items-center p-2 w-full text-base font-medium rounded-lg transition duration-75 group',
                            isActiveRoute(route('example'))
                                ? 'dark:text-gray-300 dark:bg-gray-600 text-gray-200 bg-slate-400'
                                : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700',
                        ]"
                    >
                        <Icon name="chartpie" class="w-6 h-6" />
                        <span v-if="isSidebarExpanded" class="ml-3">Exemplos</span>
                    </Link>
                </li>
            </ul>
        </div>
    </aside>
</template>
