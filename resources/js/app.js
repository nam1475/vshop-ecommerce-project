import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
/* Sweet Alert */
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
// import 'flowbite/dist/flowbite.min.css';
// import 'pagedone/src/css/pagedone.css';
// import 'pagedone/src/js/pagedone.js';
// import 'swiper/swiper-bundle.css'; 
// import 'swiper/swiper.min.css'; 
import store from './store';
import router from './router';
import { abilitiesPlugin } from '@casl/vue'
import ability from './services/ability';
import { success, error } from "@/alert.js";
import { usePage } from '@inertiajs/vue3';

const appName = import.meta.env.VITE_APP_NAME || 'VShop';
const userAbility = ability();
// const page = usePage();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(ElementPlus)
            .use(VueSweetalert2)
            .use(store)
            // .use(abilitiesPlugin, userAbility)
            // .provide('ability', ability)
            // .use(router)
            // .use(success(page))
            // .use(error(page))
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});


