<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import Breadcrumb from 'primevue/breadcrumb';
import Button from 'primevue/button';
import { Link } from '@inertiajs/vue3';
import Footer from "../Components/Footer.vue";
import axios from 'axios';

const props = defineProps({
    products_favs: Array,
    isAuthenticated: Boolean
});
console.log(props.products_favs);


function toggleFavorite(product) {
    const isFavorite = product.favorites === 1;
    axios.post(route('productFavorite'), { id: product.id, favorite: !isFavorite })
        .then(response => {
            product.favorites = isFavorite ? 0 : 1;

            if (product.favorites === 0){
                props.products_favs.splice(props.products_favs.findIndex(p => p.id === product.id),1);
            }
        })
        .catch(error => {
            console.error("Error al actualizar el estado de favorito:", error);
        });
}

const items = ref([
    { label: 'Inici', url: '/' },
    { label: 'Favorites' }
]);

const favorites = ref([]);
const loading = ref(true);

onMounted(() => {
    // Simulate API call
    setTimeout(() => {
        favorites.value = sampleFavorites;
        loading.value = false;
    }, 500);
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'EUR'
    }).format(value);
};

const removeFromFavorites = (product) => {
    // Implement remove from favorites logic
    console.log('Removing product:', product.id);
};
</script>


<template>
    <div class="min-h-screen bg-gray-50">
        <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
            <main class="container mx-auto px-4 py-8">
                <div class="max-w-7xl mx-auto">
                    <Breadcrumb :model="items" class="mb-8" />
                    <h1 class="text-4xl font-bold text-center mb-12 text-gray-800">Favorits</h1>

                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div v-if="loading" class="flex justify-center items-center py-12">
                        </div>

                        <!-- Removed the extra wrapping div and moved v-if to the grid container -->
                        <div v-if="products_favs" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div v-for="products_favs in products_favs" :key="products_favs.id"
                                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <div class="relative">
                                    <img :src="products_favs.image" :alt="products_favs.name"
                                        class="w-full h-56 object-cover" />
                                </div>
                                <div class="p-6">
                                    <Link :href="`/product/${products_favs.id}`" class="block">
                                    <h2 class="text-xl font-semibold text-gray-900 mb-3">{{ products_favs.name }}
                                    </h2>
                                    <p class="text-gray-800 text-base mb-4 line-clamp-2">{{
                                        products_favs.description }}
                                    </p>
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-blue-800 font-bold text-lg">{{
                                            formatCurrency(products_favs.price) }}</span>
                                        <span class="text-sm text-gray-800">{{ products_favs.category }}</span>
                                    </div>
                                    </Link>

                                    <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                            <span class="text-gray-800">{{ products_favs.location }}</span>
                                            <span class="mx-2 text-gray-800">•</span>
                                            <span class="text-gray-800">Hace {{ products_favs.timestamp }}</span>
                                        </div>
                                        <form @submit.prevent>
                                            <button @click="toggleFavorite(products_favs)"
                                                :class="{ 'text-red-500': products_favs.favorites === 1, 'text-gray-400': products_favs.favorites === 0 }"
                                                class="transition-colors">
                                                <svg class="h-5 w-5"
                                                    :fill="products_favs.favorites === 1 ? 'red' : 'none'"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </component>
        <Footer />
    </div>
</template>
<style scoped>
:deep(.p-breadcrumb) {
    background-color: transparent;
}

:deep(.p-breadcrumb-item-label) {
    color: #004266;
}

:deep(.p-breadcrumb-item-label:hover) {
    color: #060606 !important;
}

:deep(.p-button.p-button-text) {
    padding: 0.5rem;
}

:deep(.p-button.p-button-text:hover) {
    background: rgba(var(--primary-color), 0.1);
}
</style>