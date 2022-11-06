import './bootstrap';
import '../css/app.css';

//import { h } from 'vue';
import { createApp,h } from 'vue/dist/vue.esm-bundler';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { createStore } from 'vuex'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';



const store = createStore({
    state: {
        count: 0,
        users:[],
        parties:[]

    },
    mutations: {
        increment (state) {
            state.count++
        },
        addUser(state, users) {
            // mutate state
            state.users.push(users)
        },
        addParties(state, parties) {
            // mutate state
            state.parties.push(parties)
        }
    },


})

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(store)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
