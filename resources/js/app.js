import './bootstrap';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import DashboardLayout from './Layouts/DashboardLayout.vue';

createInertiaApp({
    resolve: name => {
        const page = resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));
        // const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        page.then((module) => {
            module.default.layout = module.default.layout || DashboardLayout;
        });
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
},
})
