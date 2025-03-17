<script setup>
import { Link, usePage, router } from "@inertiajs/vue3";
import { computed, ref, onMounted, defineEmits } from "vue";
import Icon from "@/Icons/Icons.vue";
import AOS from "aos";
import { store } from '@/Pages/Store/Mysotre';

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
};

//end functions menu page

onMounted(() => {
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
            'fixed top-0 left-0 z-40 h-screen pt-14 transition-transform bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700',
            isSidebarExpanded ? 'sidebar-expanded' : 'sidebar-collapsed'
        ]"
        aria-label="Sidenav"
        id="drawer-navigation"
    >
        <button @click="toggleSidebar" class="p-2">
            <Icon :name="isSidebarExpanded ? 'chevron-left' : 'chevron-right'" class="w-6 h-6 text-gray-300" />
        </button>
        <div class="overflow-y-auto py-5 px-3 h-full bg-white dark:bg-gray-800">
            <ul class="space-y-2">
                <li>
                    <Link
                        :href="route('dashboard')"
                        @click="handleOtherClick(route('dashboard'))"
                        :class="[
                            'flex items-center p-2 w-full text-base font-medium rounded-lg transition duration-75 group',
                            isActiveRoute(route('dashboard'))
                                ? 'text-white bg-gray-700'
                                : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700',
                        ]"
                    >
                        <Icon name="chartpie" class="w-6 h-6" />
                        <span v-if="isSidebarExpanded" class="ml-3">Painel</span>
                    </Link>
                </li>
                <!-- Add more menu items here -->
            </ul>
        </div>
    </aside>
</template>