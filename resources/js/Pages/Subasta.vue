<template>
<component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">    <div class="max-w-7xl mx-auto px-4 py-8">
      <!-- Hero Section -->
      <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg mb-12 p-8 text-white">
        <h1 class="text-4xl font-bold mb-4">Subastas en Vivo</h1>
        <p class="text-xl">Descubre productos únicos y haz tu mejor oferta</p>
      </div>

      <!-- Filtros -->
      <div class="mb-8 flex flex-wrap gap-4">
        <select class="border rounded-md px-4 py-2 text-gray-700 bg-white">
          <option>Todas las categorías</option>
          <option>Electrónicos</option>
          <option>Hogar</option>
          <option>Deportes</option>
        </select>
        <select class="border rounded-md px-4 py-2 text-gray-700 bg-white">
          <option>Ordenar por</option>
          <option>Precio más bajo</option>
          <option>Precio más alto</option>
          <option>Tiempo restante</option> 
        </select>
      </div>

      <!-- Grid de productos -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="product in products" :key="product.id" 
             class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
          <div class="relative">
            <img :src="product.image" :alt="product.name" 
                 class="w-full h-64 object-cover">
            <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full">
              {{ product.timeLeft }}
            </div>
          </div>
          
          <div class="p-6">
            <h2 class="text-2xl font-bold mb-2 text-gray-800">{{ product.name }}</h2>
            <p class="text-gray-700 mb-4">{{ product.description }}</p>
            
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
              <div class="flex justify-between items-center mb-2">
                <span class="text-gray-700">Puja actual:</span>
                <span class="text-2xl font-bold text-blue-600">${{ product.currentBid }}</span>
              </div>
              <div class="h-2 bg-gray-200 rounded-full">
                <div class="h-2 bg-blue-500 rounded-full" 
                     :style="{ width: `${(product.currentBid / 2000) * 100}%` }"></div>
              </div>
            </div>

            <form @submit.prevent="placeBid(product.id)" class="space-y-4">
              <div class="flex gap-2">
                <input 
                  type="number" 
                  v-model="bidAmounts[product.id]" 
                  :min="product.currentBid + 1" 
                  step="0.01"
                  class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-700"
                  :placeholder="`Mínimo $${product.currentBid + 1}`"
                >
                <button 
                  type="submit"
                  class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-200"
                >
                  Pujar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </component>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
// Estado para los montos de las pujas
const bidAmounts = ref({});

// Datos de ejemplo de productos
const products = ref([
  {
    id: 1,
    name: "iPhone 14 Pro",
    description: "Último modelo, 256GB, Color Space Gray",
    currentBid: 800,
    image: "https://example.com/iphone.jpg",
    timeLeft: "2 días"
  },
  {
    id: 2,
    name: "MacBook Pro M2",
    description: "16 pulgadas, 512GB SSD, 16GB RAM",
    currentBid: 1200,
    image: "https://example.com/macbook.jpg",
    timeLeft: "1 día"
  },
  {
    id: 3,
    name: "PlayStation 5",
    description: "Consola PS5 Digital Edition, Nueva",
    currentBid: 400,
    image: "https://example.com/ps5.jpg",
    timeLeft: "3 días"
  }
]);

// Función para manejar las pujas
const placeBid = (productId) => {
  const product = products.value.find(p => p.id === productId);
  const bidAmount = parseFloat(bidAmounts.value[productId]);

  if (!bidAmount) {
    alert('Por favor ingresa un monto válido');
    return;
  }

  if (bidAmount <= product.currentBid) {
    alert('La puja debe ser mayor que la puja actual');
    return;
  }

  // Actualizar la puja actual
  product.currentBid = bidAmount;
  bidAmounts.value[productId] = ''; // Limpiar el input

  alert(`¡Puja realizada con éxito! Nueva puja: $${bidAmount}`);
};
</script>

<style scoped>
.auction-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.auction-card {
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.2s;
}

.auction-card:hover {
  transform: translateY(-2px);
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
