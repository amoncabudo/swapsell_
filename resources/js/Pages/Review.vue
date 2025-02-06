<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import Review from '@/Components/Review.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  user: Object,
  isAuthenticated: Boolean
});
</script>

<template>
    <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
      <div class="max-w-5xl mx-auto py-10 sm:px-6 lg:px-8 mt-3">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <h2 class="flex justify-center items-center text-2xl font-bold mb-4">Historial de Compras</h2>
          
          <div class="space-y-4">
            <div class="p-4 border rounded-lg shadow-sm bg-gray-50">
              <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold">Laptop Dell XPS 13</h3>
                <p class="text-gray-600">Vendedor: Tienda Tecno</p>
              </div>

              <div class="mt-2">
                <Review></Review>
              </div>

              <div class="mt-2">
                <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Ver Detalles</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </component>
</template>
