<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import Breadcrumb from 'primevue/breadcrumb';
import Button from 'primevue/button';
import { Link } from '@inertiajs/vue3';
import Footer from "../Components/Footer.vue";

defineProps({
    isAuthenticated: Boolean
});


const items = ref([
    { label: 'Inici', url: '/' },
    { label: 'Favorites' }
]);

const favorites = ref([]);
const loading = ref(true);

const sampleFavorites = [
    {
        id: 1,
        name: 'Product 1',
        price: 299.99,
        image: '/images/1.jpeg',
        description: 'Description of product 1',
        category: 'Electronics',
        location: 'Barcelona',
        timestamp: '2 h'
    },
    {
        id: 2,
        name: 'Product 2',
        price: 199.99,
        image: '/path/to/image2.jpg',
        description: 'Description of product 2',
        category: 'Electronics',
        location: 'Barcelona',
        timestamp: '2 h'
    },

    {
        id: 3,
        name: 'Product 3',
        price: 199.99,
        image: '/path/to/image2.jpg',
        description: 'Description of product 2',
        category: 'Electronics',
        location: 'Barcelona',
        timestamp: '2 h'
    },
    {
        id: 4,
        name: 'Product 4',
        price: 199.99,
        image: '/path/to/image2.jpg',
        description: 'Description of product 2',
        category: 'Electronics',
        location: 'Barcelona',
        timestamp: '2 h'
    },
    {
        id: 5,
        name: 'Product 5',
        price: 199.99,
        image: '/path/to/image2.jpg',
        description: 'Description of product 2',
        category: 'Electronics',
        location: 'Barcelona',
        timestamp: '2 h'
    },
    {
        id: 6,
        name: 'Product 6',
        price: 199.99,
        image: '/path/to/image2.jpg',
        description: 'Description of product 2',
        category: 'Electronics',
        location: 'Barcelona',
        timestamp: '2 h'
    },
];

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
                            <i class="pi pi-spin pi-spinner text-4xl text-blue-600"></i>
                        </div>

                        <div v-else-if="favorites.length > 0"
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div v-for="product in favorites" :key="product.id"
                                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <div class="relative">
                                    <img :src="product.image" :alt="product.name" class="w-full h-56 object-cover" />
                                </div>
                                <div class="p-6">
                                    <Link :href="`/product/${product.id}`" class="block">
                                        <h2 class="text-xl font-semibold text-gray-900 mb-3">{{ product.name }}</h2>
                                        <p class="text-gray-800 text-base mb-4 line-clamp-2">{{ product.description }}</p>
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-blue-800 font-bold text-lg">{{ formatCurrency(product.price) }}</span>
                                            <span class="text-sm text-gray-800">{{ product.category }}</span>
                                        </div>

                                        <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                </svg>
                                                <span class="text-gray-800">{{ product.location }}</span>
                                                <span class="mx-2 text-gray-800">•</span>
                                                <span class="text-gray-800">Hace {{ product.timestamp }}</span>
                                            </div>
                                            <Button icon="pi pi-heart-fill"
                                                class="p-button-text p-button-danger !bg-white hover:!bg-red-50"
                                                @click="removeFromFavorites(product)"
                                                aria-label="Remove from favorites" />
                                        </div>
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div v-else class="text-center py-12">
                            <p class="text-gray-500 text-lg">No tens cap producte a favorits</p>
                            <Link href="/"
                                class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Explorar productes
                            </Link>
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