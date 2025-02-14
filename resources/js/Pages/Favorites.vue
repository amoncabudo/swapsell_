<script setup>
import { ref, onMounted } from 'vue';
import Breadcrumb from 'primevue/breadcrumb';
import Button from 'primevue/button';
import { Link } from '@inertiajs/vue3';
import Footer from "../Components/Footer.vue";
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { Head } from '@inertiajs/vue3';
import Cookies from "@/Components/Cookies.vue";

dayjs.extend(relativeTime);
dayjs.locale('ca'); 
import 'dayjs/locale/ca';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';
import NavbarS from '@/Layouts/NavbarS.vue';

const locations = ref({});
onMounted(() => {
  setInterval(() => { //Set interval
    props.products_favs = [...props.products_favs]; //Update products favs
  }, 60000); //60000 milliseconds
});

const props = defineProps({
    products_favs: Array,
    isAuthenticated: Boolean
});

const getLocationName = async (latitude, longitude) => { //Get location name
    const lat = Number(latitude).toFixed(6); //Latitude
    const lng = Number(longitude).toFixed(6); //Longitude
        const response = await axios.get( //Get response
            `https://nominatim.openstreetmap.org/reverse`, { //Nominatim API
                params: {
                    format: 'json', //Format
                    lat: lat, //Latitude
                    lon: lng, //Longitude
                    'accept-language': 'es', //Accept language
                    zoom: 10 //Zoom
                }
            }
        );

        if (response.data && response.data.address) { //If response data and response data address
            const address = response.data.address; //Address
            return address.city || //City
                   address.town || //Town
                   address.village || //Village
                   address.municipality || //Municipality
                   address.county || //County
                   'Ubicació no disponible'; //Location not available
        }
        return 'Ubicació no disponible'; //Location not available
};

const initializeLocations = async () => { //Initialize locations
  if (props.products_favs && props.products_favs.length > 0) { //If products favs and products favs length is greater than 0
    for (const product of props.products_favs) { //For each product
      if (product.latitude && product.longitude) { //If product latitude and product longitude
          locations.value[product.id] = await getLocationName(product.latitude, product.longitude); //Get location name
      }
    }
  }
};

onMounted(async () => {
  await initializeLocations();
});


const timeAgo = (date) => {
  return dayjs(date).fromNow();
};


function toggleFavorite(product) {
    axios.post(route('productFavorite'), { id: product.id }) //Post request
        .then(response => {
            // Delete the product from the list if we are on the favorites page
            const index = props.products_favs.findIndex(p => p.id === product.id); //Find index
            if (index !== -1) {
                props.products_favs.splice(index, 1);
            }
        })
        .catch(error => {
            console.error("Error al actualizar el estado de favorito:", error); //Error updating favorite state
        });
}

const items = ref([ //Breadcumb 
    { label: 'Inici', url: '/' }, // Label for the item from home page
    { label: 'Favorites' } // Label for the item from favorites page
]);

const favorites = ref([]);
const loading = ref(true);

onMounted(() => {
    // Simulate API call
    setTimeout(() => {
        favorites.value = sampleFavorites; //Favorites
        loading.value = false; //Loading
    }, 500); //500 milliseconds
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-ES', { //Format currency
        style: 'currency', //Style
        currency: 'EUR' //Currency
    }).format(value); //Format value
};

const removeFromFavorites = (product) => {
    // Implement remove from favorites logic
    console.log('Removing product:', product.id); //Log removing product
};

const isFavorite = (product) => {
    // If the product is in the favorites list, it means it is a favorite
    return props.products_favs.some(p => p.id === product.id); //Return true if the product is in the favorites list
};
</script>


<template>
    <Head title="Favorits"></Head>
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
                        <div v-if="products_favs && products_favs.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div v-for="products_favs in products_favs" :key="products_favs.id"
                                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <div class="relative">
                                    <img :src="`/storage/${products_favs.image}`" :alt="products_favs.name" class="w-full h-56 object-cover">
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
                                        <span class="text-sm text-gray-800">{{ products_favs.category.name }}</span>
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
                                            <span class="text-gray-800">{{ locations[products_favs.id] || 'Ubicación no disponible' }}</span>
                                            <span class="mx-2 text-gray-800">•</span>
                                            <span class="text-gray-800">{{ timeAgo(products_favs.created_at) }}</span>
                                        </div>
                                        <form @submit.prevent>
                                            <button @click="toggleFavorite(products_favs)"
                                                :class="{ 'text-red-500': isFavorite(products_favs), 'text-gray-400': !isFavorite(products_favs) }"
                                                class="transition-colors">
                                                <svg class="h-5 w-5"
                                                    :fill="isFavorite(products_favs) ? 'red' : 'none'"
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
                        <p v-else class="text-center text-gray-500 mt-4">No hi han favorits ara mateix</p>
                    </div>
                </div>
            </main>
        </component>
        <Cookies />
        <Footer/>
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