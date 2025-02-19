<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { Link, Head } from '@inertiajs/vue3';
import Cookies from "@/Components/Cookies.vue";

const props = defineProps({
  user: Object,
  formattedDate: String,
  isAuthenticated: Boolean,
  products: Array,
  auction: Array,
  soldProducts: Number,
  mediaReview: Number,
  auth: Object

})

console.log(props)

</script>

<template>

  <Head title="Perfil"></Head>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="bg-gradient-to-r from-[#ffffff] to-[#ffffff] dark:from-[#1b2e37] dark:to-[#224466] min-h-screen">
      <div class="container mx-auto p-6">
        <!-- Header of the profile -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 mb-8">
          <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-8">
            <!-- Profile image with border and shadow -->
            <div class="relative">
              <div class="w-32 h-32 rounded-full overflow-hidden">
                <img :src="`/storage/${user.image}`" alt="Foto de perfil" class="w-full h-full object-cover">
                <!-- '/storage/app/public/logo.png' -->

              </div>
            </div>

            <!-- Information of the user -->
            <div class="flex-1">
              <!--Pop-->
              <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">{{ user.name }}</h1>
              <div class="flex flex-wrap gap-4 mb-4">
                <div class="flex items-center space-x-2 text-gray-600 dark:text-white">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="dark:text-white">Membre desde {{ props.formattedDate }}</span>
                </div>
              </div>
              <div class="flex space-x-4">
                <Link :href="route('profile.edit')"
                  class="bg-blue-800 dark:bg-black text-white px-6 py-2 rounded-lg hover:bg-blue-900 dark:hover:bg-white dark:hover:text-black transition">
                Editar Perfil
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Estadísticas -->
         <div v-if="props.products">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg text-center">
            <div class="text-3xl font-bold text-blue-800 dark:text-yellow-300 mb-2">{{ props.products.length }}</div>
            <div class="text-gray-600 dark:text-white">Productes</div>
          </div>
          <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg text-center">
            <div class="text-3xl font-bold text-green-800 dark:text-yellow-300 mb-2">{{ props.soldProducts }}</div>
            <div class="text-gray-600 dark:text-white">Ventes</div>
          </div>
          <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg text-center">
            <div class="text-3xl font-bold text-yellow-800 dark:text-yellow-300 mb-2">{{ props.mediaReview }}</div>
            <div class="text-gray-600 dark:text-white">Valoració</div>
          </div>
        </div>
        
        <!-- Productos -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Els meus Productes</h2>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div v-for="product in props.products" :key="product.id" class="group relative">
              <Link :href="route('product.show', product.id)">
              <div class="aspect-square rounded-lg overflow-hidden bg-gray-200">
                <img :src="`/storage/${product.image}`" alt="Producto"
                  class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
              </div>
              <div class="mt-4">

                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ product.name }}</h3>
                <p class="text-gray-600 dark:text-white">{{ product.price }}€</p>

              </div>
              </Link>

              <Link v-if="props.auth.user.id == product.user_id" aria-label="Edit Product"  :href="route('editProductId', product.id)"
                class="absolute top-2 left-2 bg-white/80 p-2 rounded-full hover:bg-white transition">
              <svg class="w-5 h-5 text-blue-500 hover:text-blue-700 transition-colors" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-label="Editar producto">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 3l4 4m0 0l-8 8-4 1 1-4 8-8m0 0L11 7" />
              </svg>
              </Link>

              <Link v-if="props.auth.user.id == product.user_id" aria-label="Delete Product" :href="route('deleteProduct', product.id)"
                class="absolute top-2 right-2 bg-white/80 p-2 rounded-full hover:bg-white transition">
              <svg class="w-5 h-5 text-red-500 hover:text-red-700 transition-colors" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-label="Eliminar producto">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              </Link>
            </div>
          </div>
        </div>
        </div>
        <!-- Subastas -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 mt-8">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Les meves Subhastes</h2>

          </div>
          <div v-if="props.auction">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div v-for="auction in props.auction" :key="auction.id" class="group relative">
              <Link :href="route('auction')">
              <div class="aspect-square rounded-lg overflow-hidden bg-gray-200">
                <img :src="`/storage/${auction.product.image}`" alt="Producto"
                  class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
              </div>
              <div class="mt-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ auction.product.name }}</h3>
                <p class="text-gray-600 dark:text-white">Preu actual: {{ auction.product.price }}€</p>
              </div>
              </Link>
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
.aspect-square {
  aspect-ratio: 1 / 1;
}
</style>
