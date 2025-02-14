<script setup>
import Footer from "../Components/Footer.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { ref } from 'vue';
import Card from 'primevue/card';
import Timeline from 'primevue/timeline';
import { Head } from '@inertiajs/vue3';
import Cookies from "@/Components/Cookies.vue";

defineProps({
    isAuthenticated: Boolean,
});

const features = ref([ // Features of the project
    {
        title: 'Compra-Venda Segura',
        description: 'Sistema segur de transaccions amb verificació d\'usuaris i protecció contra fraus.',
        icon: 'pi pi-shield'
    },
    {
        title: 'Sostenibilitat',
        description: 'Foment de la reutilització i reducció de residus mitjançant l\'economia circular.',
        icon: 'pi pi-refresh'
    },
    {
        title: 'Comunitat Activa',
        description: 'Espai per a intercanviar, valorar i compartir experiències entre usuaris.',
        icon: 'pi pi-users'
    }
]);

const technologies = ref([ // Technologies used
    { name: 'Laravel', icon: '/images/Laravel.png' },
    { name: 'Vue.js', icon: '/images/vue.png' },
    { name: 'PrimeVue', icon: '/images/primevue.png' },
    { name: 'Tailwind CSS', icon: '/images/tailwind.png' }
]);

const objectives = ref([
    {
        title: 'Sostenibilitat',
        description: 'Promoure un consum més responsable i reduir l\'impacte ambiental.',
        status: 'success'
    },
    {
        title: 'Accessibilitat',
        description: 'Crear una plataforma intuïtiva i fàcil d\'utilitzar per a tots els usuaris.',
        status: 'success'
    },
    {
        title: 'Comunitat',
        description: 'Construir una comunitat activa i compromesa amb la sostenibilitat.',
        status: 'success'
    },
    {
        title: 'Innovació',
        description: 'Implementar tecnologies modernes per oferir la millor experiència.',
        status: 'success'
    }
]);
</script>

<template>
    <Head title = "Projecte"></Head>
      <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">

    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-12">
            <div class="text-center mb-16" data-aos="fade-down">
                <h1 class="text-5xl font-bold mt-12 mb-6 text-gray-800">SwapSell</h1>
                <h2 class="text-2xl text-gray-600">Plataforma de Compra-Venda Sostenible</h2>
            </div>

            <div class="max-w-4xl mx-auto mb-16">
                <Card class="mb-8 p-card-body">
                    <template #content>
                        <div class="p-6">
                            <h3 class="text-2xl font-bold mb-4 text-gray-800">Sobre el Projecte</h3>
                            <p class="text-gray-600 mb-4">
                                SwapSell és una innovadora plataforma de compra-venda de segona mà que neix com a part del 
                                Projecte 4: "Let's Code for a Better World". El nostre objectiu és crear un espai digital 
                                que promogui la sostenibilitat i l'economia circular, facilitant la reutilització de productes 
                                i fomentant un consum més responsable.
                            </p>
                        </div>
                    </template>
                </Card>
            </div>

            <div class="mb-16">
                <h3 class="text-3xl font-bold text-center mb-8 text-gray-800 " data-aos="fade-left">Característiques Principals</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <Card v-for="feature in features" :key="feature.title" class="feature-card" data-aos="fade-right">
                        <template #header>
                            <div class="flex justify-center p-4">
                                <i :class="feature.icon + ' text-4xl text-blue-800'"></i>
                            </div>
                        </template>
                        <template #content>
                            <div class="p-4 text-center">
                                <h4 class="text-xl font-semibold mb-2 text-gray-800">{{ feature.title }}</h4>
                                <p class="text-gray-600">{{ feature.description }}</p>
                            </div>
                        </template>
                    </Card>
                </div>
            </div>

            <div class="mb-16">
                <h3 class="text-3xl font-bold text-center mb-8 text-gray-800" data-aos="fade-up">Tecnologies Utilitzades</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div v-for="tech in technologies" :key="tech.name" 
                         class="bg-white p-6 rounded-xl shadow-md text-center hover:shadow-lg transition-shadow duration-300"
                         data-aos="zoom-out">
                        <img :src="tech.icon" :alt="tech.name" class="w-16 h-16 mx-auto mb-4">
                        <h4 class="font-semibold text-gray-800">{{ tech.name }}</h4>
                    </div>
                </div>
            </div>

                <div class="max-w-4xl mx-auto">
                <h3 class="text-3xl font-bold text-center mb-8 text-gray-800" data-aos="fade-up">Objectius del Projecte</h3>
                <Timeline :value="objectives" align="alternate" class="custom-timeline">
                    <template #content="slotProps">
                        <Card class="mb-4">
                            <template #content>
                                <div class="p-4">
                                    <h4 class="text-xl font-semibold mb-2 text-gray-800">{{ slotProps.item.title }}</h4>
                                    <p class="text-gray-600">{{ slotProps.item.description }}</p>
                                </div>
                            </template>
                        </Card>
                    </template>
                </Timeline>
            </div>
        </div>
    </div>
    </component>
    <Cookies />
    <Footer />
</template>

<style scoped>

.p-card-body{
    @apply bg-white;
}
.feature-card {
    @apply transition-all duration-300 hover:shadow-lg;
}

:deep(.p-card) {
    @apply h-full;
    @apply bg-white;
}

:deep(.p-timeline-event-opposite) {
    @apply flex items-center;
}

:deep(.p-timeline-event-content) {
    @apply min-h-0;
}

:deep(.custom-timeline .p-timeline-event-marker) {
    @apply bg-blue-800 border-blue-800;
}

:deep(.custom-timeline .p-timeline-event-connector) {
    @apply bg-gray-300;
}

</style>