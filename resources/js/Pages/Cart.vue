<script setup>
import { ref, computed } from 'vue';
import NavbarA from '@/Components/NavbarA.vue';

const products = ref([]);
const cart = ref([]);

const total = computed(() => {
  return cart.value.reduce((sum, item) => sum + item.quantity * item.price, 0);
});
</script>

<template>
  <NavbarA></NavbarA>
  <div class="flex flex-col lg:flex-row h-screen overflow-hidden">
    <!-- Columna izquierdax -->
     
    <div class="w-full lg:w-1/2 p-4">
      <h2 class="text-xl flex justify-center font-extrabold mb-4 text-center">Lista de Productos</h2>
      <div class="bg-gray-300 p-4 min-h-[200px] overflow-y-auto rounded">
        <ul class="flex flex-col space-y-4">
          <li v-for="n in 12" :key="n" class="font-bold border-b-2 border-dotted border-gray-400 pb-2 rounded">
            Foto --- Producto {{ n }}
          </li>
        </ul>
      </div>
    </div>

    <!-- Columna derecha -->
    <div class="w-full lg:w-1/2 p-4 flex flex-col justify-between">
      <div>
        <h2 class="text-xl font-bold mb-4 text-center">Resumen</h2>
        <div class="bg-gray-300 min-h-[80px] flex justify-start items-center rounded shadow-lg">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10 ml-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
          </svg>
          <p class="ml-2 text-xl">Resum de la compra</p>
        </div>
        <p class="mt-4 text-lg">Preu estimat:</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.bg-gray-100 {
  overflow-y: auto;
  scrollbar-width: none;
}

.bg-gray-100::-webkit-scrollbar {
  display: none;
}
</style>