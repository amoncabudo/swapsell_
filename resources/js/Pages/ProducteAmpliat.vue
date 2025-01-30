<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';



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
    <div class="flex flex-col lg:flex-row h-screen overflow-y-auto space-y-4 lg:space-y-0 lg:space-x-4 md:ml-32">
      
      <!-- Primer bloque -->
      <div class="w-full lg:w-2/3 p-4 flex flex-col items-center justify-center">
        <p class="text-xl font-bold mb-4">{{ product.name }}</p>
        <img src="/images/machine.jpg" alt="pop" class="mb-4 rounded max-w-full h-auto">
        <div class="flex space-x-4">
          <button @click="addToBasket" class="bg-SubastaButton1 text-white font-bold py-2 px-4 rounded hover:bg-slate-800">
            Compra
          </button>
          <button class="bg-SubastaButton2 text-white font-bold py-2 px-4 rounded hover:bg-amber-600">
            Subasta
          </button>
        </div>
      </div>
  
      <!-- Segundo bloque -->
      <div class="w-full lg:w-2/3 p-4 flex flex-col space-y-4 justify-center">
        <!-- Primer div -->
        <div class="bg-gray-200 w-full lg:w-4/5 p-4 flex justify-between items-center rounded shadow-2xl">
          <div class="text-left">
            <p>Nom producte : {{ product.name }}</p>
            <p>Preu: {{ product.price }} €</p>
            <p>Descripcio del producte: {{ product.description }}</p>
          </div>
          <img src="/images/machine.jpg" alt="popaopaopa" class="rounded w-24 h-28">
        </div>
  
        <!-- Segundo div -->
        <div class="bg-gray-200 w-full lg:w-4/5 p-4 flex flex-col rounded shadow-2xl">
          <p class="text-4xl mb-4">Informació del venedor</p>
          <div class="flex items-center">
            <img src="/images/User.png" alt="User" class="w-20 h-20 rounded-full mr-4">
            <div class="text-left">
              <p class="mt-1">Nom i Cognom: {{ props.user.name }} {{ props.user.surname }}</p>
              <p>Membre des de:</p>
              <p>Mitjana de rating:</p>
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
