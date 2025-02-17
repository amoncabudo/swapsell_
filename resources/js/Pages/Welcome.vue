<script setup>
import Footer from '../Components/Footer.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Cookies from "@/Components/Cookies.vue";
import NavbarS from '@/Layouts/NavbarS.vue';
import { ref, onMounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

defineProps({
    isAuthenticated: Boolean,
});


const steps = ref([ // Steps of the welcome page
    {
        title: 'Crea el teu compte',
        description: 'Registra\'t gratuïtament i verifica el teu perfil en menys d\'un minut.',
        delay: 200
    },
    {
        title: 'Publica o explora',
        description: 'Puja els teus productes o navega per milers d\'articles únics.',
        delay: 400
    },
    {
        title: 'Compra o ven',
        description: 'Realitza transaccions segures amb el nostre sistema de pagament protegit.',
        delay: 600
    }
]);

const featuredProducts = ref([]);
const trendingCategories = ref([]);

onMounted(async () => {
    const response = await axios.get(route('products.featured')); // Get the featured products
    featuredProducts.value = response.data; // Set the featured products
});

onMounted(async () => {
    const response = await axios.get(route('categories.trending')); // Get the trending categories
    trendingCategories.value = response.data.map((category, index) => ({
        id: category.id,
        name: category.name,
        items: category.items,
        image: `/storage/products/${category.image}`,
        delay: (index + 1) * 100
        }));
});
</script>

<template>
    <Head title="Inici"></Head>
    <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">

        <div class="min-h-screen bg-gray-50">
            <section class="relative min-h-screen py-20 bg-gradient-to-r from-[#004266] to-[#004266] dark:from-[#1b2e37] dark:to-[#224466]">
                <div class="container mx-auto px-4">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div class="text-white" data-aos="fade-right">
                            <h1 class="text-6xl font-bold mb-6">Descobreix. Compra.<br>Ven.</h1>
                            <p class="text-xl text-gray-300 mb-8">La teva plataforma de confiança per comprar i vendre
                                productes únics de segona mà.</p> 
                            <div class="flex gap-4">
                                <button
                                    class="border border-white text-gray-100 px-8 py-3 rounded-lg hover:bg-white hover:text-gray-900 transition-all dark:border-gray-800 dark:bg-gray-900 dark:text-gray-100 dark:hover:bg-gray-100 dark:hover:text-gray-900 dark:hover:border-gray-200">
                                    <a href="/products">Començar ara</a>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4" data-aos="fade-left">
                            <div v-for="(product, index) in featuredProducts" :key="product.id"
                                :data-aos="index % 2 === 0 ? 'fade-up' : 'fade-down'"
                                :data-aos-delay="100 * (index + 1)" 
                                class="featured-item">
                                <img :src="`/storage/products/${product.image}`" :alt="product.name" class="w-full h-48 object-cover">
                                <div class="p-2 bg-white">
                                    <h2 class="text-black font-semibold">{{ product.name }}</h2>
                                    <p class="text-bold text-red-800">{{ product.price }} €</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="py-20 dark:bg-gray-800 bg-gray-100">
                <div class="container mx-auto px-4">
                    <h2 class="text-4xl font-bold text-center mb-16 text-black dark:text-gray-100" data-aos="fade-down">Tendències</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div v-for="category in trendingCategories" :key="category.id" class="trending-card"
                            data-aos="zoom-in" :data-aos-delay="category.delay">
                            <div class="relative h-80">
                                <img :src="`${category.image}`" :alt="category.name"
                                    class="w-full h-full object-cover rounded-xl">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent rounded-xl">
                                    <div class="absolute bottom-6 left-6 text-white">
                                        <h3 class="text-2xl font-bold mb-2 text-white">{{ category.name }}</h3>
                                        <p class="text-sm text-gray-300">{{ category.items }} productes</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="py-20 bg-white dark:bg-gray-800">
                <div class="container mx-auto px-4">
                    <h2 class="text-4xl font-bold text-center mb-16 text-black dark:text-gray-100" data-aos="fade-down">Com funciona</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                        <div v-for="(step, index) in steps" :key="index" class="step-card" data-aos="fade-up"
                            :data-aos-delay="step.delay">
                            <div class="step-number">{{ index + 1 }}</div>
                            <!-- Adjusted text color for dark mode -->
                            <h3 class="text-xl font-semibold mb-4 text-black dark:text-white">{{ step.title }}</h3>
                            <p class="text-gray-600 dark:text-gray-300">{{ step.description }}</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="py-20 sect-background dark:bg-gray-900">
                <div class="container mx-auto px-4 text-center" data-aos="fade-up">
                    <h2 class="text-4xl font-bold text-white mb-8 dark:text-white">Uneix-te a la nostra comunitat</h2>
                    <p class="text-xl text-white mb-8 max-w-2xl mx-auto dark:text-gray-100">
                        Més de 50.000 usuaris ja confien en nosaltres per comprar i vendre els seus productes.
                    </p>
                    <Link :href="route('register')">    
                        <button class="dark:bg-gray-950 bg-white text-blue-800 px-8 py-3 rounded-lg hover:bg-gray-100 dark:text-gray-100 dark:hover:bg-gray-100 dark:hover:text-gray-900 transition-all">
                            <p href="/register" class="">Crear compte gratuït</p>
                        </button>
                    </Link>
                </div>
            </section>


            <hr class="border-gray-200">

            <Cookies />

            <Footer />
        </div>
    </component>
</template>

<style scoped>
.sect-background {
    background: linear-gradient(to right, #004266, #004266);
}
.dark .sect-background {
    background: linear-gradient(to right, #002233, #001122); /* Darker gradient for dark mode */
}
.featured-item {
    @apply bg-gray-800/50 rounded-lg overflow-hidden transition-all duration-300 hover:bg-gray-800;
}

.trending-card {
    @apply transition-all duration-300 hover:-translate-y-2 hover:shadow-xl cursor-pointer;
}

.step-card {
    @apply text-center p-8 rounded-xl bg-gray-50 dark:bg-gray-800 transition-all duration-300 hover:shadow-lg relative;
}


.step-number {
    @apply w-12 h-12 text-white rounded-full flex items-center justify-center text-xl font-bold absolute -top-6 left-1/2 -translate-x-1/2;
    background-color: #004266
}
[data-aos] {
    @apply transition-all duration-500;
}
</style>
