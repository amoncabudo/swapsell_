<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import Review from '@/Components/Review.vue';
import { Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import Cookies from '@/Components/Cookies.vue';

const props = defineProps({
  isAuthenticated: Boolean,
  transaction: Array,
});
console.log(props.transaction);
</script>

<template>
  <Head title="Historial"></Head>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="max-w-5xl mx-auto py-10 sm:px-6 lg:px-8 mt-3">
      <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6">
        <h1 class="text-3xl font-bold text-[#004265] dark:text-white mb-8 text-center">Historial de Compres</h1>
        
        <div class="space-y-6">
  <div 
    v-for="transaction in transaction" 
    :key="transaction.id" 
    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden"
  >
    <!-- Header con gradiente -->
    <div class="bg-gradient-to-r from-[#004265] to-[#005285] px-6 py-4">
      <p class="text-white text-sm font-medium">Compra: #{{ transaction.id }}</p>
    </div>
    
    <!-- Contenido de la transacción -->
    <div class="p-6">
      <div class="flex flex-col md:flex-row items-center gap-6">
        <!-- Imagen del producto -->
        <div class="w-full md:w-1/3">
          <div class="relative group">
            <img 
              :src="`/storage/${transaction.product.image}`" 
              :alt="transaction.product.name" 
              class="w-full h-48 object-cover rounded-xl transition-transform duration-300 group-hover:scale-105"
            >
            <div class="absolute inset-0 bg-[#004265] opacity-0 group-hover:opacity-10 transition-opacity duration-300 rounded-xl"></div>
          </div>
          <h2 class="text-lg font-semibold text-[#004265] dark:text-blue-300 mt-4 text-center">{{ transaction.name }}</h2>
        </div>

        <!-- Detalles de la transacción -->
        <div class="w-full md:w-2/3 grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Comprador -->
          <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 hover:shadow-md transition-all duration-300 border border-[#004265]/10 dark:border-gray-600">
            <p class="text-sm text-[#0a111f] dark:text-gray-300 mb-2">Comprat per:</p>
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-[#004265]/10 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-[#004265] dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <div>
                <p class="font-semibold text-[#004265] dark:text-blue-300">{{ transaction.buyer.name }}</p>
                <p class="text-sm text-gray-700 dark:text-gray-400">Comprador</p>
              </div>
            </div>
          </div>

          <!-- Vendedor -->
          <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 hover:shadow-md transition-all duration-300 border border-[#004265]/10 dark:border-gray-600">
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">Venut per:</p>
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-[#004265]/10 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-[#004265] dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <div>
                <p class="font-semibold text-[#004265] dark:text-blue-300">{{ transaction.user?.name }}</p>
                <p class="text-sm text-gray-700 dark:text-gray-400">Venedor</p>
              </div>
            </div>
            <!-- Componente de revisión -->
            <div class="mt-4">
              <Review 
                :userId="transaction.user?.id" 
                :productId="transaction.product?.id" 
                :valoration="transaction.review?.valoration === 1"
              />                    
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
      </div>
    </div>
  </component>
  <Cookies />
</template>
