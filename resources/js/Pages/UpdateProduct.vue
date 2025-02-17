<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { Head } from '@inertiajs/vue3';
import Cookies from "@/Components/Cookies.vue";

const props = defineProps({
    isAuthenticated: Boolean,
    product: Object
});

console.log(props.product);
let form = useForm({
    id: props.product.id ?? "",
    name: props.product.name ?? "",
    description: props.product.description ?? "",
    price: props.product.price ?? null,
    longitude: props.product.longitude ?? null,
    latitude: props.product.latitude ?? null,
    status: props.product.status ?? "active",
    category_id: props.product.category_id ?? "Hogar"
});


</script>
<template>
  <Head title = "Editar producte"></Head>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
  <div id="app" class="p-8 bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
      <h1 class="text-2xl font-bold mb-6 text-center text-black">Formulario de Registro</h1>
      <form @submit.prevent="form.post(route('updateProduct', { id: form.id }))">
        <div class="mb-4">
          <label for="id" class="block text-gray-700 font-medium mb-2"></label>
          <input
            type="hidden"
            id="id"
            name="id"
            v-model="form.id"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
            required
          />
        </div>
        
        <div class="mb-4">
          <label for="name" class="block text-black font-medium mb-2">Nombre:</label>
          <input
            type="text"
            id="name"
            name="name"
            v-model="form.name"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 text-black"
            required
          />
        </div>

        <div class="mb-4">
          <label for="description" class="block text-black font-medium mb-2">Descripción:</label>
          <textarea
            id="description"
            name="description"
            v-model="form.description"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 text-black"
            required
          ></textarea>
        </div>

        <div class="mb-4">
          <label for="price" class="block text-black font-medium mb-2">Precio:</label>
          <input
            type="number"
            id="price"
            name="price"
            v-model="form.price"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 text-black"
            required
          />
        </div>

        <div class="mb-4">
          <label for="latitude" class="block text-black font-medium mb-2">Latitud:</label>
          <input
            type="number"
            step="any"
            id="latitude"
            name="latitude"
            v-model="form.latitude"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 text-black"
            required
          />
        </div>

        <div class="mb-4">
          <label for="longitude" class="block text-black font-medium mb-2">Longitud:</label>
          <input
            type="number"
            step="any"
            id="longitude"
            name="longitude"
            v-model="form.longitude"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 text-black"
            required
          />
        </div>




        <div class="mb-4">
          <label for="status" class="block text-black font-medium mb-2">Categoria</label>
          <select
            id="category_id"
            name="category_id"
            v-model="form.category_id"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 text-black"
            required
          >
            <option value="1" class="text-black">Hogar</option>
            <option value="2" class="text-black">Tecnologia</option>
            <option value="3" class="text-black">Deportes</option>
            <option value="4" class="text-black">Moda</option>
            <option value="5" class="text-black">Salud y belleza</option>
            <option value="6" class="text-black">Juguetes</option>
          </select>
        </div>

        <button
          type="submit"
          class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-300"
        >
          Enviar
        </button>
      </form>

      <div v-if="submitted" class="mt-6">
        <h2 class="text-xl font-semibold mb-4 text-black">Datos enviados:</h2>
        <pre class="bg-gray-100 p-4 rounded-lg text-black">{{ form }}</pre>
      </div>
    </div>
  </div>
</component>
<Cookies />
</template>