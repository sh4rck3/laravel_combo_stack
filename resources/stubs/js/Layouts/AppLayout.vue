<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import NavBar from './NavBar/NavBar.vue'
import SideBar from './SideBar/SideBar.vue'
import Breadcrumbs from './Breadcrumbs/Breadcrumbs .vue'


defineProps({
    title: String,
})

const isSidebarExpanded = ref(true)

const handleToggleSidebar = (expanded) => {
    isSidebarExpanded.value = expanded
    console.log(isSidebarExpanded.value)
}

</script>
<template>
    <div>
        <Head :title="title" />

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            
            <NavBar />

            <!-- Page Heading -->
            <header v-if="$slots.header" :class="[isSidebarExpanded ? 'header-expanded' : 'header-collapsed']">
                <div class="max-w-8xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <Breadcrumbs :name="title"  />
                </div>
            </header>

            <SideBar   @toggle-sidebar="handleToggleSidebar" />

            <!-- Page Content -->
            <main :class="[isSidebarExpanded ? 'main-expanded' : 'main-collapsed']">
                <slot :isSidebarExpanded="isSidebarExpanded" />
            </main>
        </div>
    </div>
</template>
<style>
.header-expanded {
    padding-left: 16rem; /* 64px */
}

.header-collapsed {
    padding-left: 4rem; /* 16px */
}

.main-expanded {
    margin-left: 16rem; /* 64px */
}

.main-collapsed {
    margin-left: 4rem; /* 16px */
}
</style>


