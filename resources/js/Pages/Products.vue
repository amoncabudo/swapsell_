<script setup>
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { ref } from 'vue';
import axios from 'axios';

function toggleFavorite(product) {
  const isFavorite = product.favorites === 1;
  axios.post(route('productFavorite'), { id: product.id, favorite: !isFavorite })
    .then(response => {
      product.favorites = isFavorite ? 0 : 1;
    })
    .catch(error => {
      console.error("Error al actualizar el estado de favorito:", error);
    });
}

const props = defineProps({
  products: Array,
  isAuthenticated: Boolean
})
console.log(props.products)
</script>
<template>
  <div class="bg-gray-50 min-h-screen">
    <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
      <!-- Hero Section con wave background -->
      <div class="wave-bg pt-24 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h1 class="text-4xl font-bold text-white text-center mb-8">
            Encuentra lo que buscas
          </h1>
          <div class="max-w-3xl mx-auto">
            <div class="relative">
              <input type="text" placeholder="¿Qué estás buscando?"
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
            <button class="category-active px-4 py-2 rounded-full flex items-center space-x-2">
              <span class="text-lg">⭐</span>
              <span>Todo</span>
            </button>
            <button class="category-inactive px-4 py-2 rounded-full flex items-center space-x-2">
              <span class="text-lg">📱</span>
              <span>Electrónicos</span>
            </button>
            <button class="category-inactive px-4 py-2 rounded-full flex items-center space-x-2">
              <span class="text-lg">🪑</span>
              <span>Muebles</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Grid de productos -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="product in products" :key="product.id"
            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="relative">
              <img :src="`/uploads/products/${product.image}`" :alt="product.name" class="w-full h-56 object-cover">
            </div>
            <div class="p-6">
              <Link :href="`/product/${product.id}`" class="block">
              <h2 class="text-xl font-semibold text-gray-900 mb-3">{{ product.name }}</h2>
              <p class="text-gray-800 text-base mb-4 line-clamp-2">{{ product.description }}</p>
              <div class="flex justify-between items-center mb-4">
                <span class="text-blue-800 font-bold text-lg">{{ product.price }} €</span>
                <span class="text-sm text-gray-800">Electrónicos</span>
              </div>
              </Link>

              <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                <div class="flex items-center">
                  <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  </svg>
                  <span class="text-gray-800">{{ product.location || 'Ubicación no disponible' }}</span>
                  <span class="mx-2 text-gray-800">•</span>
                  <span class="text-gray-800">Hace 2h</span>
                </div>

                <form @submit.prevent>
                  <button @click="toggleFavorite(product)"
                    :class="{ 'text-red-500': product.favorites === 1, 'text-gray-400': product.favorites === 0 }"
                    class="transition-colors">
                    <svg class="h-5 w-5" :fill="product.favorites === 1 ? 'red' : 'none'" stroke="currentColor"
                      viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                  </button>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Botón flotante de venta -->
      <Link href="/sell"
        class="fixed bottom-8 right-8 bg-custom-blue text-white rounded-full p-4 shadow-lg transition-all duration-300 transform hover:-translate-y-1 hover:bg-custom-blue-dark">
      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      </Link>
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