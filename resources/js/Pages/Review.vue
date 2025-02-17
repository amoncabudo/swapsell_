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
  <Head title = "Historial"></Head>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="max-w-5xl mx-auto py-10 sm:px-6 lg:px-8 mt-3">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <h2 class="flex justify-center items-center text-2xl font-bold mb-4">Historial de Compras</h2>
        
        <div v-if="transaction.length === 0" class="text-center text-gray-600">
          No hi ha productes comprats.
        </div>

        <div class="space-y-4">
          <div 
            v-for="transaction in transaction" 
            :key="transaction.id" 
            class="p-4 border rounded-lg shadow-sm bg-gray-50 flex items-center"
          >
          
            <!-- Left section: Image and name of the products -->
            <div class="w-1/3 flex items-center space-x-4">
              <img :src="`/storage/${transaction.product.image}`" :alt="transaction.product.name" class="w-24 h-24 object-cover rounded">
              <h3 class="text-lg font-semibold">{{ transaction.name }}</h3>
            </div>

            <!-- Section central: Buyer -->
            <div class="w-1/3 text-center">
              <p class="text-gray-600 font-semibold">Comprat per:</p>
              <p class="text-gray-800 font-bold">{{ transaction.buyer.name }}</p>
            </div>

            <!-- Right section: Seller -->
            <div class="w-1/3 text-right">
              <p class="text-gray-600 font-semibold">Venut per:</p>
              <p class="text-gray-800 font-bold">{{ transaction.user?.name }}</p>
              <Review :userId="transaction.user?.id" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </component>
  <Cookies />
</template>



