<template>
    <div class="min-h-screen bg-gray-50">
        <main class="container mx-auto px-4 py-8">
            <h1 class="text-5xl font-bold text-center mb-12 text-gray-800">Sobre Nosaltres</h1>

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
                                <p class="mb-4">
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
                                <p>
                                    El nostre objectiu és oferir una experiència intuïtiva, versàtil i segura perquè
                                    els usuaris puguin:
                                </p>
                                <ul class="list-disc pl-6 mb-4">
                                    <li>Crear i llistar productes que ja no necessiten</li>
                                    <li>Editar o actualitzar la informació dels articles publicats</li>
                                    <li>Eliminar publicacions fàcilment quan ja no estiguin disponibles</li>
                                    <li>Comprar productes de qualitat a preus accessibles</li>
                                    <li>Vendre de manera ràpida i eficient</li>
                                </ul>
                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Section -->
            <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">Equipo</h2>
            <div class="max-w-5xl mx-auto">
                <!-- Primera fila -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-6">
                    <div v-for="member in firstRow" :key="member.id" 
                         class="card-container"
                         data-aos="fade-right"
                         :data-aos-delay="member.id * 100">
                        <div class="card">
                            <!-- Front Card -->
                            <div class="card-front bg-white rounded-xl shadow-md p-6">
                                <div class="w-24 h-24 mx-auto mb-4">
                                    <img :src="member.image" :alt="`${member.name} ${member.surname}`"
                                        class="w-full h-full object-contain">
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ member.name }} {{ member.surname }}</h3>
                                <p class="text-sm text-gray-600">{{ member.role }}</p>
                                <p v-if="member.rank" class="text-sm font-medium text-indigo-600">{{ member.rank }}</p>
                            </div>
                            <!-- Back Card -->
                            <div class="card-back bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-md p-6">
                                <div class="text-white">
                                    <h3 class="text-lg font-semibold mb-2">{{ member.name }}</h3>
                                    <p class="text-sm mb-2">{{ member.role }}</p>
                                    <p v-if="member.rank" class="text-sm font-medium">{{ member.rank }}</p>
                                    <a href="#" class="inline-block mt-4 text-white hover:text-indigo-200 transition-colors">
                                        Ver más →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Segunda fila -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <div v-for="member in secondRow" :key="member.id" 
                         class="card-container"
                         data-aos="fade-left"
                         :data-aos-delay="(member.id - 3) * 100">
                        <div class="card">
                            <!-- Front Card -->
                            <div class="card-front bg-white rounded-xl shadow-md p-6">
                                <div class="w-24 h-24 mx-auto mb-4">
                                    <img :src="member.image" :alt="`${member.name} ${member.surname}`"
                                        class="w-full h-full object-contain">
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ member.name }} {{ member.surname }}</h3>
                                <p class="text-sm text-gray-600">{{ member.role }}</p>
                                <p v-if="member.rank" class="text-sm font-medium text-indigo-600">{{ member.rank }}</p>
                            </div>
                            <!-- Back Card -->
                            <div class="card-back bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-md p-6">
                                <div class="text-white">
                                    <h3 class="text-lg font-semibold mb-2">{{ member.name }}</h3>
                                    <p class="text-sm mb-2">{{ member.role }}</p>
                                    <p v-if="member.rank" class="text-sm font-medium">{{ member.rank }}</p>
                                    <a href="#" class="inline-block mt-4 text-white hover:text-indigo-200 transition-colors">
                                        Ver más →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <Footer />
    </div>
</template>

<script>
import Footer from "../Components/Footer.vue";
export default {
    components: {
        Footer
    },
    data() {
        return {
            teamMembers: [
                { id: 1, name: 'Arnau', surname: 'Garcia', role: 'Programador Full Stack', rank: '', image: 'images/logo1.png' },
                { id: 2, name: 'Marc', surname: 'Cufí', role: 'Programador Full Stack', rank: '', image: 'images/logo2.png' },
                { id: 3, name: 'Alex', surname: 'Moncayo', role: 'Programador Full Stack', rank: '', image: 'images/logo3.png' },
                { id: 4, name: 'Joel', surname: 'Pages', role: 'Programador Full Stack', rank: 'Scrum Master', image: 'images/logo4.png' },
                { id: 5, name: 'Carla', surname: 'De Caprio', role: 'Programador Full Stack', rank: '', image: 'images/logo5.png' },
                { id: 6, name: 'Jhosep', surname: 'Espinoza ', role: 'Programador Full Stack', rank: '', image: 'images/logo6.png' },
            ]
        };
    },
    computed: {
        firstRow() {
            return this.teamMembers.slice(0, 3);
        },
        secondRow() {
            return this.teamMembers.slice(3, 6);
        }
    }
}
</script>

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
</style>