<script setup>
import { Link } from '@inertiajs/vue3';
import { defineProps, ref, onMounted, computed } from 'vue';
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
    props.products = [...props.products];
  }, 60000);
});
// Añadimos un ref para las categorías
// Definir props primero
const props = defineProps({
  products: Array,
  isAuthenticated: Boolean
});

// Añadimos un ref para las categorías y ubicaciones
const categories = ref([]);
const locations = ref({});

// Función para obtener las categorías
const fetchCategories = async () => {
    const response = await axios.get(route('categories.trending'));
    categories.value = response.data.filter(category => category.id !== 7);
};

// Función para obtener el nombre de la ubicación
const getLocationName = async (latitude, longitude) => {
    const lat = Number(latitude).toFixed(6);
    const lng = Number(longitude).toFixed(6);
        const response = await axios.get(
            `https://nominatim.openstreetmap.org/reverse`, {
                params: {
                    format: 'json',
                    lat: lat,
                    lon: lng,
                    'accept-language': 'es',
                    zoom: 10
                }
            }
        );

        if (response.data && response.data.address) {
            const address = response.data.address;
            return address.city || 
                   address.town || 
                   address.village || 
                   address.municipality || 
                   address.county ||
                   'Ubicación no disponible';
        }
        return 'Ubicación no disponible';
};

// Función para inicializar las ubicaciones
const initializeLocations = async () => {
  if (props.products && props.products.length > 0) {
    for (const product of props.products) {
      if (product.latitude && product.longitude) {
          locations.value[product.id] = await getLocationName(product.latitude, product.longitude);
      }
    }
  }
};

onMounted(async () => {
  await fetchCategories();
  await initializeLocations();
});

function toggleFavorite(product) {
    axios.post(route('productFavorite'), { id: product.id }) 
        .then(response => {
            // Actualizar el estado del favorito basado en la respuesta del servidor
            product.is_favorite = response.data.is_favorite;
        })
        .catch(error => {
            console.error("Error al actualizar el estado de favorito:", error);
        });
}

const isFavorite = (product) => {
    return product.is_favorite;
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
</script>
<template>
  <div class="bg-gray-50 min-h-screen">
    <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
      <!-- Hero Section con wave background -->
      <div class="wave-bg pt-24 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h1 class="text-4xl font-bold text-white text-center mb-8">
            Troba el que busques
          </h1>
          <div class="max-w-3xl mx-auto">
            <div class="relative">
              <input aria-label="search bar" type="text" placeholder="¿Què estàs buscant?"
                class="w-full pl-12 pr-4 py-4 rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-white/50">
              <div class="absolute left-4 top-4">
                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Categorías con efecto glass -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
        <div class="glass-effect rounded-xl p-4">
          <div class="flex space-x-4 overflow-x-auto py-2">
            <button 
              @click="selectedCategory = 'all'"
              :class="[
                'px-4 py-2 rounded-full flex items-center space-x-2',
                selectedCategory === 'all' ? 'category-active' : 'category-inactive'
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
                selectedCategory === category.name ? 'category-active' : 'category-inactive'
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

      <!-- Grid de productos -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div v-if="filteredProducts" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="product in filteredProducts" :key="product.id"
            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="relative">
              <img :src="`/storage/${product.image}`" :alt="product.name" class="w-full h-56 object-cover">
            </div>  
            <div class="p-6">
              <Link :href="route('product.show', product.id)" class="block">
              <h2 class="text-xl font-semibold text-gray-900 mb-3">{{ product.name }}</h2>
              <p class="text-gray-800 text-base mb-4 line-clamp-2">{{ product.description }}</p>
              <div class="flex justify-between items-center mb-4">
                <span class="text-blue-800 font-bold text-lg">{{ product.price }} €</span>
                <span class="text-sm text-gray-800">{{ product.category.name }}</span>
              </div>
              </Link>

              <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                <div class="flex items-center">
                  <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  </svg>
                  <span class="text-gray-800">{{ locations[product.id] || 'Ubicación no disponible' }}</span>
                  <span class="text-gray-800">{{ timeAgo(product.created_at) }}</span>
                </div>

                <form @submit.prevent>
                  <button aria-label="Favorite Product" @click="toggleFavorite(product)"
                    :class="{ 'text-red-500': product.is_favorite, 'text-gray-400': !product.is_favorite }"
                    class="transition-colors">
                    <svg class="h-5 w-5"
                      :fill="product.is_favorite ? 'red' : 'none'"
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
        
        <!-- Mensaje cuando no hay productos -->
        <div v-else class="text-center py-12">
          <div class="bg-white rounded-lg shadow-md p-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No hi ha productes amb aquesta categoría</h3>
            <p class="mt-2 text-sm text-gray-500">Intenta buscar amb una altre categoria o torna més tard.</p>
          </div>
        </div>
      </div>
      <!-- Botones flotantes -->
      <div class="fixed bottom-8 right-8 flex space-x-4">
        <!-- Botón de venta -->
        <Link aria-label="addProduct" href="/sell"
          class="bg-custom-blue text-white rounded-full p-4 shadow-lg transition-all duration-300 transform hover:-translate-y-1 hover:bg-custom-blue-dark flex items-center">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span class="ml-2">Vendre</span>
        </Link>

        <!-- Botón de subastas -->
        <Link aria-label="Create Auction" href="/create-auction"
          class="bg-custom-blue text-white rounded-full p-4 shadow-lg transition-all duration-300 transform hover:-translate-y-1 hover:bg-custom-blue-dark flex items-center">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          <span class="ml-2">Subhastes</span>
        </Link>

        <!-- Botón de chat -->
        <Link aria-label="Products Map" href="/mapa"
          class="bg-custom-blue text-white rounded-full p-4 shadow-lg transition-all duration-300 transform hover:-translate-y-1 hover:bg-custom-blue-dark flex items-center">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span class="ml-2">Mapa</span>
        </Link>
      </div>
    </component>
  </div>
</template>


<style scoped>
/* Colores personalizados */
.bg-custom-blue {
  background-color: #004266;
}

.bg-custom-blue-dark {
  background-color: #003252;
}

.bg-custom-blue-light {
  background-color: #006699;
}

.text-custom-blue {
  color: #004266;
}

.text-custom-blue-dark {
  color: #003252;
}

.border-custom-blue {
  border-color: #004266;
}

/* Efectos */
.glass-effect {
  background: rgb(255, 255, 255);
  box-shadow: 0 8px 32px 0 rgba(0, 66, 102, 0.2);
}

/* Fondos y animaciones */
.wave-bg {
  background: linear-gradient(45deg, #004266, #006699);
  background-size: 400% 400%;
  animation: gradient 15s ease infinite;
}

@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }

  50% {
    background-position: 100% 50%;
  }

  100% {
    background-position: 0% 50%;
  }
}

/* Categorías */
.category-active {
  background-color: #004266;
  color: white;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.category-inactive {
  background-color: white;
  color: #374151;
}

.category-inactive:hover {
  background-color: #F9FAFB;
  color: #004266;
}

/* Animaciones */
@keyframes float {

  0%,
  100% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(-2px);
  }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

/* Estilos para el login */
.logo-container {
  position: relative;
  z-index: 1;
}

.logo-container::before {
  content: '';
  position: absolute;
  inset: -5px;
  background: linear-gradient(45deg, #004266, #006699);
  border-radius: 50%;
  z-index: -1;
  filter: blur(15px);
  opacity: 0.5;
}

/* Inputs personalizados */
.form-group input {
  backdrop-filter: blur(8px);
}

/* Botones */
.btn-gradient {
  background: linear-gradient(45deg, #004266, #006699);
  transition: all 0.3s ease;
}

.btn-gradient:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 66, 102, 0.3);
}

/* Efectos hover */
.hover-scale {
  transition: transform 0.3s ease;
}

.hover-scale:hover {
  transform: scale(1.05);
}
</style>