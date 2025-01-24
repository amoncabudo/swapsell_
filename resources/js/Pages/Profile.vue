<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  user: Object,
  isAuthenticated: Boolean,
  products: Array

})

console.log(props.user)
console.log(props.products)

</script>

<template>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
  <div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto p-6">
      <!-- Cabecera del perfil -->
      <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-8">
          <!-- Imagen de perfil con borde y sombra -->
          <div class="relative">
            <div class="w-32 h-32 rounded-full overflow-hidden ring-4 ring-blue-500 ring-offset-4">
              <img src="/images/User.png" alt="Foto de perfil" class="w-full h-full object-cover">
            </div>
            <button class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full hover:bg-blue-600 transition">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4"/>
              </svg>
            </button>
          </div>
          
          <!-- Información del usuario -->
          <div class="flex-1">
            <!--Pop-->
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ user.name }}</h1>
            <p class="text-gray-600 mb-4">{{ user.surname }}</p>
            <div class="flex flex-wrap gap-4 mb-4">
              <div class="flex items-center space-x-2 text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                </svg>
                <span>Barcelona, España</span>
              </div>
              <div class="flex items-center space-x-2 text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Miembro desde 2024</span>
              </div>
            </div>
            <p class="text-gray-700 mb-4">
              Descripción del usuario. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>
            <div class="flex space-x-4">
              <button class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                Editar Perfil
              </button>
              <button class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-50 transition">
                Configuración
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Estadísticas -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-lg text-center">
          <div class="text-3xl font-bold text-blue-500 mb-2">28</div>
          <div class="text-gray-600">Productos</div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-lg text-center">
          <div class="text-3xl font-bold text-green-500 mb-2">156</div>
          <div class="text-gray-600">Ventas</div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-lg text-center">
          <div class="text-3xl font-bold text-yellow-500 mb-2">4.8</div>
          <div class="text-gray-600">Valoración</div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-lg text-center">
          <div class="text-3xl font-bold text-purple-500 mb-2">42</div>
          <div class="text-gray-600">Seguidores</div>
        </div>
      </div>

      <!-- Productos -->
      <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-800">Mis Productos</h2>
          <button class="text-blue-500 hover:text-blue-600 transition">
            Ver todos
          </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div v-for="product in props.products" :key="product.id" class="group relative">
            <div class="aspect-square rounded-lg overflow-hidden bg-gray-200">
              <img :src="product.image" alt="Producto" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
            </div>
            <div class="mt-4">
              <h3 class="text-lg font-semibold text-gray-800">{{ product.name }}</h3>
              <p class="text-gray-600">{{ product.price }}€</p>
            </div>
            <!-- <Link :href="route('product.show', product.id)" class="absolute inset-0 flex items-center justify-center bg-black/50 text-white opacity-0 group-hover:opacity-100 transition duration-300">
              Ver Producto
            </Link> -->
            <Link :href="route('updateProductId', product.id)" class="absolute top-2 left-2 bg-white/80 p-2 rounded-full hover:bg-white transition">
              <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path d="M12 14l9-5-9-5-9 5 9 5z"/>
              </svg>
            </Link>
            <button class="absolute top-2 right-2 bg-white/80 p-2 rounded-full hover:bg-white transition">
              <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</component>
</template>

<style scoped>
.aspect-square {
  aspect-ratio: 1 / 1;
}
</style>