<script setup>
import Footer from "../Components/Footer.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
defineProps({
    isAuthenticated: Boolean
})
const teamMembers = ref([
    { id: 1, name: 'Arnau', surname: 'Garcia', role: 'Programador Backend', rank: '', image: '/images/logo1.png' },
    { id: 2, name: 'Marc', surname: 'Cufí', role: 'Programador Backend', rank: '', image: '/images/logo2.png' },
    { id: 3, name: 'Alex', surname: 'Moncayo', role: 'Programador Full Stack', rank: '', image: '/images/logo3.png' },
    { id: 4, name: 'Joel', surname: 'Pages', role: 'Programador Backend', rank: 'Scrum Master', image: '/images/logo4.png' },
    { id: 5, name: 'Carla', surname: 'De Caprio', role: 'Programador Frontend', rank: '', image: '/images/logo5.png' },
    { id: 6, name: 'Jhosep', surname: 'Espinoza ', role: 'Programador Full Stack', rank: '', image: '/images/logo6.png' },
]);

const firstRow = computed(() => teamMembers.value.slice(0, 3));
const secondRow = computed(() => teamMembers.value.slice(3, 6));

const scrollToTeam = () => {
    const teamSection = document.querySelector('#team-section');
    if (teamSection) {
        teamSection.scrollIntoView({ behavior: 'smooth' });
    }
};
const items = ref([
    { label: 'Inici', url: '/' },
    { label: 'Sobre Nosaltres' }
]);

</script>

<template>
    <Head title = "Sobre nosaltres"></Head>
<component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
<div class="min-h-screen bg-gray-50">

        <main class="container mx-auto px-4 py-8">
            <Breadcrumb :model="items" class=" mt-12" />
            <h1 class="text-4xl font-bold text-center mb-12 text-gray-800">Sobre Nosaltres</h1>

            <!-- Info Section -->
            <div class="max-w-4xl mx-auto mb-16">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="md:flex">
                        <div class="md:w-1/3">
                            <img class="w-full h-full object-cover" src="/images/card2.png" alt="Equipo trabajando">
                        </div>
                        <div class="p-8 md:w-2/3">
                            <h2 class="text-3xl font-bold text-gray-800 mb-4">GRUP 4</h2>
                            <div class="prose prose-lg text-gray-600">
                                <p class="mb-2">
                                    Som un grup d'estudiants apassionats de 2n de Desenvolupament d'Aplicacions Web
                                    (DAW),
                                    compromesos amb aprendre, crear i innovar al món digital. Actualment,
                                    formem part del Projecte 4: Let's Code for a Better World, un desafiament que ens
                                    impulsa
                                    a desenvolupar solucions tecnològiques amb impacte positiu a la nostra societat.
                                </p>
                                <p class="mb-4">
                                    En aquesta ocasió, estem treballant en una plataforma de venda de segona mà,
                                    dissenyada per a fomentar la sostenibilitat, la reutilització i la col·laboració
                                    entre
                                    persones.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll Arrow -->
            <div class="flex justify-center items-center w-full my-16">
                <button @click="scrollToTeam" aria-label="Desplaçar-se a la secció de l'equip"
                    class="animate-bounce p-2 rounded-full bg-blue-800 text-white hover:bg-blue-600 transition-colors duration-300 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </button>
            </div>

            <!-- Team Section -->
            <h2 class="text-3xl font-bold text-center mb-8 text-gray-800 team-title" id="team-section"
                data-aos="fade-right" data-aos-delay="100">Equipo</h2>
            <div class="max-w-5xl mx-auto">
                <!-- Primera fila -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-6">
                    <div v-for="member in firstRow" :key="member.id" class="card-container" data-aos="fade-right"
                        :data-aos-delay="member.id * 100">
                        <div class="card">
                            <!-- Front Card -->
                            <div class="card-front bg-white rounded-xl shadow-md p-6">
                                <div class="w-24 h-24 mx-auto mb-4">
                                    <img :src="member.image" :alt="`${member.name} ${member.surname}`"
                                        class="w-full h-full object-contain">
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ member.name }} {{ member.surname }}
                                </h3>
                                <p class="text-sm text-gray-600">{{ member.role }}</p>
                                <p v-if="member.rank" class="text-sm font-medium text-indigo-600">{{ member.rank }}</p>
                            </div>
                            <!-- Back Card -->
                            <div
                                class="card-back bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-md p-6">
                                <div class="text-white">
                                    <h3 class="text-lg font-semibold mb-2">{{ member.name }}</h3>
                                    <p class="text-sm mb-2">{{ member.role }}</p>
                                    <p v-if="member.rank" class="text-sm font-medium">{{ member.rank }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Segunda fila -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <div v-for="member in secondRow" :key="member.id" class="card-container" data-aos="fade-left"
                        :data-aos-delay="(member.id - 3) * 100">
                        <div class="card">
                            <!-- Front Card -->
                            <div class="card-front bg-white rounded-xl shadow-md p-6">
                                <div class="w-24 h-24 mx-auto mb-4">
                                    <img :src="member.image" :alt="`${member.name} ${member.surname}`"
                                        class="w-full h-full object-contain">
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ member.name }} {{ member.surname }}
                                </h3>
                                <p class="text-sm text-gray-600">{{ member.role }}</p>
                                <p v-if="member.rank" class="text-sm font-medium text-indigo-600">{{ member.rank }}</p>
                            </div>
                            <!-- Back Card -->
                            <div
                                class="card-back bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-md p-6">
                                <div class="text-white">
                                    <h3 class="text-lg font-semibold mb-2">{{ member.name }}</h3>
                                    <p class="text-sm mb-2">{{ member.role }}</p>
                                    <p v-if="member.rank" class="text-sm font-medium">{{ member.rank }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <Footer />
        
    </div>
</component>
</template>

<style scoped>
.card-container {
    perspective: 1000px;
    height: 200px;
}

.card {
    position: relative;
    width: 100%;
    height: 100%;
    transform-style: preserve-3d;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-front,
.card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.card-back {
    transform: rotateY(180deg);
}

.card-container:hover .card {
    transform: rotateY(180deg);
}

.prose ul {
    margin-top: 1rem;
    margin-bottom: 1rem;
}

.prose ul li {
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}

.team-title {
    margin-top: 80px;
}
:deep(.p-breadcrumb) {
    background-color: transparent;
}
:deep(.p-breadcrumb-item-label) {
    color: #004266;
}

:deep(.p-breadcrumb-item-label:hover) {
    color: #060606 !important;
}
</style>