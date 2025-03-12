import './bootstrap'
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'

// Importar plugins
import PermissionsPlugin from './Plugins/Permissions'

// Importar bibliotecas externas
import vSelect from "vue-select";
import 'vue-select/dist/vue-select.css'

import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

import ToastPlugin from 'vue-toast-notification'
import 'vue-toast-notification/dist/theme-sugar.css'

import { TailwindPagination } from 'laravel-vue-pagination'

import Vuex from 'vuex'
import { store } from './Store/MyStore'

import AOS from 'aos'
import 'aos/dist/aos.css'

import print from 'vue3-print-nb'

import VueMask from '@devindex/vue-mask'

import FullCalendar from '@fullcalendar/vue3'

import VueApexCharts from "vue3-apexcharts"

import VCalendar from 'v-calendar'
import 'v-calendar/style.css'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

// Inicializar AOS
document.addEventListener('DOMContentLoaded', () => {
    AOS.init();
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(PermissionsPlugin) // Registrando o plugin de permissões
            .use(VueSweetalert2)
            .use(ToastPlugin)
            .use(Vuex)
            .use(AOS)
            .use(VueMask)
            .use(store)
            .use(VueApexCharts)
            .use(VCalendar, {})
            .use(print)
            .component('Pagination', TailwindPagination)
            .component('v-select', vSelect)
            .component('FullCalendar', FullCalendar)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});