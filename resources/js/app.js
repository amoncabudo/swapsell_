import '../css/app.css';
import './bootstrap';
import 'flowbite'; //Flowbite

//Animations On Scroll
import AOS from 'aos';
import 'aos/dist/aos.css'; 

//PrimeVue
import PrimeVue from 'primevue/config'; //PrimeVue
import Aura from '@primevue/themes/aura'; //PrimeVue Theme
import 'primeicons/primeicons.css' //PrimeVue Icons
import Breadcrumb from 'primevue/breadcrumb'; //PrimeVue Breadcrumb

//Import for Tabs
import Tabs from 'primevue/tabs';//PrimeVue Tabs
import TabList from 'primevue/tablist';//PrimeVue TabList
import Tab from 'primevue/tab';//PrimeVue Tab
import TabPanels from 'primevue/tabpanels';//PrimeVue TabPanels
import TabPanel from 'primevue/tabpanel';//PrimeVue TabPanel

//Import for Accordion
import Accordion from 'primevue/accordion';//PrimeVue Accordion
import AccordionPanel from 'primevue/accordionpanel';//PrimeVue AccordionPanel
import AccordionHeader from 'primevue/accordionheader';//PrimeVue AccordionHeader
import AccordionContent from 'primevue/accordioncontent';//PrimeVue AccordionContent



import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';                   // optional



import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin)
           .use(PrimeVue, {
               theme: {
                   preset: Aura
               }
           })
           .use(ZiggyVue);
        
        // Register PrimeVue components globally
        app.component('Breadcrumb', Breadcrumb);
        app.component('Tabs', Tabs);
        app.component('TabList', TabList);
        app.component('Tab', Tab);
        app.component('TabPanels', TabPanels);
        app.component('TabPanel', TabPanel);
        
        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

//Animations On Scroll Start
AOS.init({
    duration: 1000,
    once: true,
    offset: 100
});
