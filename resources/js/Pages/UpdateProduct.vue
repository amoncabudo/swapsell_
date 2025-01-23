<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';

defineProps({
    isAuthenticated: Boolean,
});

let form = useForm({  
        id: "",   
        name: "",
        description: "",
        price: null,
        longitude: null,
        latitude: null,
        status: "active",
      });


</script>
<template>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
  <div id="app" class="p-8 bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
      <h1 class="text-2xl font-bold mb-6 text-center">Formulario de Registro</h1>
      <form @submit.prevent="form.post(route('updateProduct'))">
        <div class="mb-4">
          <label for="id" class="block text-gray-700 font-medium mb-2">Id Producte:</label>
          <input
            type="number"
            id="id"
            name="id"
            v-model="form.id"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
            required
          />
        </div>
        
        <div class="mb-4">
          <label for="name" class="block text-gray-700 font-medium mb-2">Nombre:</label>
          <input
            type="text"
            id="name"
            name="name"
            v-model="form.name"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
            required
          />
        </div>

        <div class="mb-4">
          <label for="description" class="block text-gray-700 font-medium mb-2">Descripción:</label>
          <textarea
            id="description"
            name="description"
            v-model="form.description"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
            required
          ></textarea>
        </div>

        <div class="mb-4">
          <label for="price" class="block text-gray-700 font-medium mb-2">Precio:</label>
          <input
            type="number"
            id="price"
            name="price"
            v-model="form.price"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
            required
          />
        </div>

        <div class="mb-4">
          <label for="longitude" class="block text-gray-700 font-medium mb-2">Longitud:</label>
          <input
            type="number"
            step="any"
            id="longitude"
            name="longitude"
            v-model="form.longitude"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
            required
          />
        </div>

        <div class="mb-4">
          <label for="latitude" class="block text-gray-700 font-medium mb-2">Latitud:</label>
          <input
            type="number"
            step="any"
            id="latitude"
            name="latitude"
            v-model="form.latitude"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
            required
          />
        </div>

        <div class="mb-4">
          <label for="status" class="block text-gray-700 font-medium mb-2">Estado:</label>
          <select
            id="status"
            name="status"
            v-model="form.status"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
            required
          >
            <option value="active">Activo</option>
            <option value="inactive">Inactivo</option>
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
        <h2 class="text-xl font-semibold mb-4">Datos enviados:</h2>
        <pre class="bg-gray-100 p-4 rounded-lg">{{ form }}</pre>
      </div>
    </div>
  </div>
</component>
</template>