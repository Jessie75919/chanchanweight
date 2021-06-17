require('./bootstrap');

import {
    App as InertiaApp,
    plugin as InertiaPlugin,
} from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { createApp, h } from 'vue';
import dayjs from 'dayjs';
import 'dayjs/locale/zh-tw';
dayjs.locale('zh-tw');
import 'mosha-vue-toastify/dist/style.css';
import VCalendar from 'v-calendar';

const el = document.getElementById('app');
const app = createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
});
app.mixin({ methods: { route } })
    .use(InertiaPlugin)
    .use(VCalendar, {})
    .mount(el);

app.provide('dayjs', dayjs);

InertiaProgress.init({ color: '#4B5563' });
