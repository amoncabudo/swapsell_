<script setup>
import { Link, Head } from '@inertiajs/vue3';
import { defineProps, ref, onMounted, computed } from 'vue';
import Cookies from "@/Components/Cookies.vue";
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

dayjs.extend(relativeTime);
dayjs.locale('ca');
import 'dayjs/locale/ca';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import axios from 'axios';

const timeAgo = (date) => {
  return dayjs(date).fromNow();
};
onMounted(() => { 
  setInterval(() => {
    props.products = [...props.products]; // Update the products
  }, 60000); // Update the products every minute
});
// Define the categories
const props = defineProps({
  products: Array,
  isAuthenticated: Boolean
});
// Define the categories
const categories = ref([]); // Define the categories
const locations = ref({}); // Define the locations

// Function to get the categories
const fetchCategories = async () => { // Function to get the categories
  const response = await axios.get(route('categories.trending')); // Get the categories
  categories.value = response.data.filter(category => category.id !== 7); // Get the categories
};

// function to get the location name
const getLocationName = async (latitude, longitude) => { // Function to get the location name
  const lat = Number(latitude).toFixed(6); // Convert the latitude to a number with 6 decimal places
  const lng = Number(longitude).toFixed(6); // Convert the longitude to a number with 6 decimal places
  const response = await axios.get( // Get the location name
    `https://nominatim.openstreetmap.org/reverse`, { // Get the location name
    params: { // Get the location name
      format: 'json', // Get the location name
      lat: lat, // Get the location name
      lon: lng, // Get the location name
      'accept-language': 'es', // Get the location name
      zoom: 10
    }
  }
  );

  if (response.data && response.data.address) { // If the response data and the response data address are true
    const address = response.data.address; // Get the address
    return address.city || // Return the address city
      address.town || // Return the address town
      address.village || // Return the address village
      address.municipality || // Return the address municipality
      address.county || // Return the address county
      'Ubicació no disponible';
  }
  return 'Ubicació no disponible'; 
};

// Function to initialize the locations
const initializeLocations = async () => { // Function to initialize the locations
  if (props.products && props.products.length > 0) { // If the products and the products length are true
    for (const product of props.products) { // For the products
      if (product.latitude && product.longitude) { // If the product latitude and the product longitude are true
        locations.value[product.id] = await getLocationName(product.latitude, product.longitude); // Get the location name
      }
    }
  }
};

onMounted(async () => { // On mounted
  await fetchCategories(); // Fetch the categories
  await initializeLocations(); // Initialize the locations
});

// Function to toggle the favorite
function toggleFavorite(product) { // Function to toggle the favorite
  axios.post(route('productFavorite'), { id: product.id }) // Post the product favorite
    .then(response => { // Then the response
      // Update the favorite state based on the server response
      product.is_favorite = response.data.is_favorite; // Update the favorite state based on the server response
    })
    .catch(error => { // Catch the error
      console.error("Error al actualizar l'estat de favorit:", error); // Error updating the favorite state
    });
}

const isFavorite = (product) => { // Function to check if the product is favorite
  return product.is_favorite; // Return the product is favorite
};

// Función auxiliar para asignar emojis según la categoría
const getCategoryEmoji = (categoryName) => {
  const emojiMap = {
    'Llar': '🏠',
    'Tecnologia': '📱',
    'Esports': '⚽',
    'Moda': '👕',
    'Salut i Bellesa': '💄',
    'Joguines': '🎮'
  };
  return emojiMap[categoryName] || '📦';
};

const selectedCategory = ref('all');

// Función para filtrar productos por categoría
const filteredProducts = computed(() => {
  if (selectedCategory.value === 'all') {
    return props.products;
  } else {
    const filtered = props.products.filter(product =>
      product.category.name === selectedCategory.value
    );
    return filtered.length > 0 ? filtered : null;
  }
});
// Function for search products
const searchQuery = ref('');
const searchedProducts = computed(() => { // Computed for search products
  if (!searchQuery.value) { // If the search query is empty, return the filtered products
    return filteredProducts.value; // Return the filtered products
  }
  return filteredProducts.value.filter(product => // Return the filtered products
    product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || // If the product name includes the search query, return the product
    product.description.toLowerCase().includes(searchQuery.value.toLowerCase()) // If the product description includes the search query, return the product
  );
});
</script>
<template>
  <div class="text-gray-900 dark:text-gray-100 dark:bg-gray-900">
    <Head title="Productes"></Head>
    <div class="bg-gray-50 min-h-screen dark:bg-gray-900">
      <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
        <!-- Hero Section con wave background -->
        <div class="pt-24 pb-16 bg-gradient-to-r from-[#004266] to-[#006699] dark:from-[#1b2e37] dark:to-[#224466]">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-white text-center mb-8">
              Troba el que busques
            </h1>
            <div class="max-w-3xl mx-auto">
              <div class="relative">
                <input
                  aria-label="search bar"
                  type="text"
                  placeholder="¿Què estàs buscant?"
                  v-model="searchQuery"
                  class="w-full pl-12 pr-4 py-4 rounded-xl text-black dark:text-gray-100 dark:bg-gray-700 shadow-lg focus:outline-none focus:ring-2 focus:ring-white/50"
                />
                <div class="absolute left-4 top-4">
                  <svg
                    class="h-6 w-6 text-gray-400 dark:text-gray-300"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                    />
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Categories with glass effect -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
          <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-4">
            <div class="flex space-x-4 overflow-x-auto py-2">
              <button
                @click="selectedCategory = 'all'"
                :class="[
                  'px-4 py-2 rounded-full flex items-center space-x-2',
                  selectedCategory === 'all'
                    ? 'bg-[#004266] text-white shadow-md'
                    : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-600 hover:text-[#004266]',
                ]"
              >
                <span class="text-lg">⭐</span>
                <span>Tot</span>
              </button>
              <button
                v-for="category in categories"
                :key="category.id"
                @click="selectedCategory = category.name"
                :class="[
                  'px-4 py-2 rounded-full flex items-center space-x-2',
                  selectedCategory === category.name
                    ? 'bg-[#004266] text-white shadow-md'
                    : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-600 hover:text-[#004266]',
                ]"
              >
                <span class="text-lg">
                  {{ getCategoryEmoji(category.name) }}
                </span>
                <span>{{ category.name }}</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Products grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <div
            v-if="searchedProducts && searchedProducts.length"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
          >
            <div
              v-for="product in searchedProducts"
              :key="product.id"
              class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
            >
              <div class="relative">
                <img
                  :src="`/storage/${product.image}`"
                  :alt="product.name"
                  class="w-full h-56 object-cover"
                />
              </div>
              <div class="p-6">
                <Link :href="route('product.show', product.id)" class="block">
                  <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3">
                    {{ product.name }}
                  </h2>
                  <p class="text-gray-800 dark:text-gray-200 text-base mb-4 line-clamp-2">
                    {{ product.description }}
                  </p>
                  <div class="flex justify-between items-center mb-4">
                    <span class="text-blue-800 dark:text-blue-400 font-bold text-lg">
                      {{ product.price }} €
                    </span>
                    <span class="text-sm text-gray-800 dark:text-gray-200">
                      {{ product.category.name }}
                    </span>
                  </div>
                </Link>

                <div class="mt-2 flex items-center justify-between text-sm text-gray-500 dark:text-gray-300">
                  <div class="flex items-center">
                    <svg
                      class="h-4 w-4 mr-1"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                      />
                    </svg>
                    <span class="text-gray-800 dark:text-gray-200">
                      {{ locations[product.id] || 'Ubicación no disponible' }}
                    </span>
                    <span class="mx-2 text-gray-800 dark:text-gray-200">•</span>
                    <span class="text-gray-800 dark:text-gray-200">
                      {{ timeAgo(product.created_at) }}
                    </span>
                  </div>

                  <form @submit.prevent>
                    <button
                      aria-label="Favorite Product"
                      @click="toggleFavorite(product)"
                      :class="{
                        'text-red-500': product.is_favorite,
                        'text-gray-400 dark:text-gray-300': !product.is_favorite,
                      }"
                      class="transition-colors"
                    >
                      <svg
                        class="h-5 w-5"
                        :fill="product.is_favorite ? 'red' : 'none'"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                        />
                      </svg>
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Message when there are no products -->
          <div v-else class="text-center py-12">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
              <svg
                class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-300"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                />
              </svg>
              <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">
                No hi ha productes amb aquesta categoria
              </h3>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">
                Intenta cercar amb una altra categoria o torna més tard.
              </p>
            </div>
          </div>
        </div>

        <!-- Floating buttons -->
        <div class="fixed bottom-8 right-8 flex space-x-4">
          <!-- Sell button -->
          <Link
            aria-label="addProduct"
            href="/sell"
            class="bg-[#004266] text-white rounded-full p-4 shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex items-center"
          >
            <svg
              class="h-6 w-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 4v16m8-8H4"
              />
            </svg>
            <span class="ml-2">Vendre</span>
          </Link>

          <!-- Auction button -->
          <Link
            aria-label="Create Auction"
            href="/create-auction"
            class="bg-[#004266] text-white rounded-full p-4 shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex items-center"
          >
            <svg
              class="h-6 w-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
              />
            </svg>
            <span class="ml-2">Subhastes</span>
          </Link>

          <!-- Chat button -->
          <Link
            aria-label="Products Map"
            href="/mapa"
            class="bg-[#004266] text-white rounded-full p-4 shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex items-center"
          >
            <svg
              class="h-6 w-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            <span class="ml-2">Mapa</span>
          </Link>
        </div>
      </component>
      <Cookies />
    </div>
  </div>
</template>