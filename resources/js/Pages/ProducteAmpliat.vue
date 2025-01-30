<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { onMounted } from 'vue';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

const props = defineProps({
    isAuthenticated: Boolean,
    product: Object,
    user: Object,
});
console.log(props.product);

let map;

onMounted(() => {
    // Inicializar el mapa
    map = L.map('map').setView([props.product.latitude, props.product.longitude], 13);
    
    // Añadir capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Crear icono personalizado
    const customIcon = L.icon({
        iconUrl: '/images/marker.png',
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -40]
    });

    // Añadir marcador con la ubicación del producto
    L.marker([props.product.latitude, props.product.longitude], { icon: customIcon })
        .bindPopup(`<b>${props.product.name}</b><br>${props.product.price}€`)
        .addTo(map);
});
</script>

<template>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Contenedor principal con efecto glass -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 mb-8">
          <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sección de imagen y botones -->
            <div class="w-full lg:w-1/2">
              <div class="bg-white rounded-xl shadow-lg p-4">
                <h1 class="text-2xl font-bold text-gray-800 mb-4 text-center">{{ product.name }}</h1>
                <div class="relative group">
                  <img 
                    :src="`/storage/${product.image}`" 
                    :alt="product.name" 
                    class="w-full h-[286px] object-cover rounded-lg transition-transform duration-300 group-hover:scale-105"
                  >
                  <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg"></div>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex justify-center space-x-4 mt-6">
                  <button class="flex items-center space-x-2 bg-SubastaButton1 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Comprar ahora</span>
                  </button>
                  <button class="flex items-center space-x-2 bg-SubastaButton2 text-white px-6 py-3 rounded-lg hover:bg-amber-600 transition-all duration-300 transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Subastar</span>
                  </button>
                </div>
              </div>
            </div>
            
            <!-- Sección de información -->
            <div class="w-full lg:w-1/2 space-y-6">
              <!-- Información del vendedor -->
              <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Información del vendedor</h2>
                <div class="flex items-center space-x-4">
                  <img src="/images/User.png" alt="User" class="w-16 h-16 rounded-full border-2 border-gray-200">
                  <div>
                    <p class="font-semibold text-lg">{{ props.user.name }} {{ props.user.surname }}</p>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                      <span>⭐ 4.8</span>
                      <span>•</span>
                      <span>Miembro desde 2024</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Detalles del producto -->
              <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Detalles del producto</h2>
                <div class="space-y-4">
                  <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-black">Nombre:</span>
                    <span class="font-semibold text-black">{{ product.name }}</span>
                  </div>
                  <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-black">Precio:</span>
                    <span class="font-semibold text-xl text-green-600">{{ product.price }}€</span>
                  </div>
                  <div class="flex justify-between items-center py-2">
                    <span class="text-black">Descripción:</span>
                    <span class="text-black">{{ product.description }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Nueva sección de comentarios -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 mt-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-6">Comentarios</h2>
          
          <!-- Formulario para nuevo comentario -->
          <div class="mb-8">
            <div class="flex items-start space-x-4">
              <img :src="'/images/User.png'" alt="User Avatar" class="w-10 h-10 rounded-full">
              <div class="flex-1">
                <textarea 
                  class="w-full p-4 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Escribe tu comentario..."
                  rows="3"
                ></textarea>
                <button class="mt-2 bg-SubastaButton1 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-all duration-300">
                  Publicar comentario
                </button>
              </div>
            </div>
          </div>

          <!-- Lista de comentarios -->
          <div class="space-y-6">
            <!-- Comentarios con scroll estilizado -->
            <div class="max-h-[300px] overflow-y-auto pr-4 space-y-6 custom-scrollbar">
              <div v-for="i in 8" :key="i" class="flex items-start space-x-4">
                <img src="/images/User.png" alt="User" class="w-10 h-10 rounded-full">
                <div class="flex-1">
                  <div class="bg-white/80 backdrop-blur-sm rounded-lg p-4 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-center mb-2">
                      <p class="font-semibold text-gray-800">Usuario {{ i }}</p>
                      <span class="text-sm text-gray-500">Hace {{ i }} días</span>
                    </div>
                    <p class="text-gray-600">Todo perfecto con la compra.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 mt-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-6">Ubicación del producto</h2>
          <div id="map" class="h-[400px] w-full rounded-lg"></div>
        </div>
      </div>
    </div>
  </component>
</template>

<style scoped>
.glass-effect {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 8px 32px 0 rgba(0, 66, 102, 0.2);
}

/* Añadir estilos para el mapa */
#map {
    z-index: 1;
}

/* Estilización del scrollbar */
.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.4);
  border-radius: 10px;
  margin: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #004266;
  border-radius: 10px;
  border: 2px solid rgba(255, 255, 255, 0.4);
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #003252;
}

/* Soporte para Firefox */
.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: #004266 rgba(255, 255, 255, 0.4);
}
</style>
