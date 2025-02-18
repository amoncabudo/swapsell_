<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import Review from '@/Components/Review.vue';
import { Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import Cookies from "@/Components/Cookies.vue";

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
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <h1 class="text-3xl font-bold text-[#004265] mb-8 text-center">Historial de Compres</h1>
        
        <div class="space-y-6">
          <div 
            v-for="transaction in transaction" 
            :key="transaction.id" 
            class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden"
          >
            <!-- Cabecera con el color corporativo -->
            <div class="bg-gradient-to-r from-[#004265] to-[#005285] px-6 py-4">
              <p class="text-white text-sm font-medium">
                Referència: #{{ transaction.id }}
              </p>
            </div>
            
            <!-- Contenido principal -->
            <div class="p-6">
              <div class="flex flex-col md:flex-row items-center gap-6">
                <!-- Imagen y producto -->
                <div class="w-full md:w-1/3">
                  <div class="relative group">
                    <img 
                      :src="`/storage/${transaction.product.image}`" 
                      :alt="transaction.product.name" 
                      class="w-full h-48 object-cover rounded-xl transition-transform duration-300 group-hover:scale-105"
                    >
                    <div class="absolute inset-0 bg-[#004265] opacity-0 group-hover:opacity-10 transition-opacity duration-300 rounded-xl"></div>
                  </div>
                  <h2 class="text-lg font-semibold text-[#004265] mt-4 text-center">
                    {{ transaction.name }}
                  </h2>
                </div>

                <!-- Información de la transacción -->
                <div class="w-full md:w-2/3 grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Comprador -->
                  <div class="bg-gray-50 rounded-xl p-4 hover:shadow-md transition-all duration-300 border border-[#004265]/10">
                    <p class="text-sm" style="color: #0a111f; margin-bottom: 0.5rem;">Comprat per:</p>
                    <div class="flex items-center space-x-3">
                      <div class="w-10 h-10 bg-[#004265]/10 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#004265]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                      </div>
                      <div>
                        <p class="font-semibold text-[#004265]">{{ transaction.buyer.name }}</p>
                        <p class="text-sm text-gray-700">Comprador</p>
                      </div>
                    </div>
                  </div>

                  <!-- Vendedor -->
                  <div class="bg-gray-50 rounded-xl p-4 hover:shadow-md transition-all duration-300 border border-[#004265]/10">
                    <p class="text-sm text-gray-700 mb-2">Venut per:</p>
                    <div class="flex items-center space-x-3">
                      <div class="w-10 h-10 bg-[#004265]/10 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#004265]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                      </div>
                      <div>
                        <p class="font-semibold text-[#004265]">{{ transaction.user?.name }}</p>
                        <p class="text-sm text-gray-700">Venedor</p>
                      </div>
                    </div>
                    <div class="mt-4">
                      <Review :userId="transaction.user?.id" />
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

<style scoped>
.shadow-xl {
  box-shadow: 0 20px 25px -5px rgba(0, 66, 101, 0.1), 0 10px 10px -5px rgba(0, 66, 101, 0.04);
}

.hover\:shadow-xl:hover {
  box-shadow: 0 25px 30px -5px rgba(0, 66, 101, 0.15), 0 15px 15px -5px rgba(0, 66, 101, 0.08);
}

/* Gradiente personalizado para el hover */
.hover\:bg-gradient:hover {
  background: linear-gradient(to right, #004265, #005285);
}
</style>



