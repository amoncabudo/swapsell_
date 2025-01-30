<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';



const props = defineProps({
    isAuthenticated: Boolean,
    product: Object,
    user: Object,
});
console.log(props.product);
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
  
        <!-- Tercer div -->
        <div class="bg-gray-200 w-full lg:w-4/5 p-4 flex flex-col rounded shadow-2xl">
          <p class="text-4xl mb-4">Comentaris</p>
          <div class="space-y-4">
            <div class="flex items-center">
              <img src="/images/User.png" alt="User" class="w-10 h-10 rounded-full mr-4">
              <p class="mt-1">Comentari:</p>
            </div>
            <div class="flex items-center">
              <img src="/images/User.png" alt="User" class="w-10 h-10 rounded-full mr-4">
              <p class="mt-1">Comentari:</p>    
            </div>
          </div>
        </div>
      </div>
    </div>
  </component>
</template>

<style scoped></style>
