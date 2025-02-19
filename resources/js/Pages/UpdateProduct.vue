<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { Head } from '@inertiajs/vue3';
import Cookies from "@/Components/Cookies.vue";
import { ref } from 'vue';

const props = defineProps({
    isAuthenticated: Boolean,
    product: Object
});

const imagePreview = ref(props.product.image ? `/storage/${props.product.image}` : null);

let form = useForm({
    id: props.product.id ?? "",
    name: props.product.name ?? "",
    description: props.product.description ?? "",
    price: props.product.price ?? null,
    longitude: props.product.longitude ?? null,
    latitude: props.product.latitude ?? null,
    status: props.product.status ?? "active",
    category_id: props.product.category_id ?? "Hogar",
    image: null
});

const handleImageChange = (e) => {
    const file = e.target.files[0];
    form.image = file;
    
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};
</script>
<template>
  <Head title = "Editar producte"></Head>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
  <div id="app" class="p-8 from-indigo-100 to-blue-200 dark:from-[#1b2e37] dark:to-[#224466] min-h-screen flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md w-full max-w-md">
      <h1 class="text-2xl font-bold mb-6 text-center text-black dark:text-white">Formulario de Registro</h1>
      <form @submit.prevent="form.post(route('updateProduct', { id: form.id }), {
        forceFormData: true,
        preserveScroll: true,
    })">
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
          <label for="name" class="block text-black dark:text-white font-medium mb-2">Nombre:</label>
          <input
            type="text"
            id="name"
            name="name"
            v-model="form.name"
            class="dark:bg-gray-950 dark:text-white w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 text-black"
            required
          />
        </div>

        <div class="mb-4">
          <label for="description" class="block text-black dark:text-white font-medium mb-2">Descripción:</label>
          <textarea
            id="description"
            name="description"
            v-model="form.description"
            class="dark:bg-gray-950 dark:text-white w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 text-black"
            required
          ></textarea>
        </div>

        <div class="mb-4">
          <label for="price" class="block text-black dark:text-white font-medium mb-2">Precio:</label>
          <input
            type="number"
            id="price"
            name="price"
            v-model="form.price"
            class="dark:bg-gray-950 dark:text-white w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 text-black"
            required
          />
        </div>

        <div class="mb-4">
          <label for="latitude" class="block text-black dark:text-white font-medium mb-2">Latitud:</label>
          <input
            type="number"
            step="any"
            id="latitude"
            name="latitude"
            v-model="form.latitude"
            class="dark:bg-gray-950 dark:text-white w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 text-black"
            required
          />
        </div>

        <div class="mb-4">
          <label for="longitude" class="block text-black dark:text-white font-medium mb-2">Longitud:</label>
          <input
            type="number"
            step="any"
            id="longitude"
            name="longitude"
            v-model="form.longitude"
            class="dark:bg-gray-950 dark:text-white w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 text-black"
            required
          />
        </div>

        <div class="mb-4">
          <label for="image" class="block text-black dark:text-white font-medium mb-2">Imatge:</label>
          <input
            type="file"
            id="image"
            @change="handleImageChange"
            accept="image/*"
            class="dark:text-white w-full rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 text-black"
          />
          
          <div v-if="imagePreview" class="mt-4">
            <img 
              :src="imagePreview" 
              alt="Vista previa" 
              class="w-32 h-32 object-cover rounded-lg border border-gray-300"
            />
          </div>
          
          <div v-else-if="props.product.image" class="mt-4">
            <p class="text-sm text-gray-600 dark:text-white mb-2">Imatge actual:</p>
            <img 
              :src="`/storage/${props.product.image}`" 
              alt="Imagen actual" 
              class="w-32 h-32 object-cover rounded-lg border border-gray-300"
            />
          </div>
        </div>

        <div class="mb-4">
          <label for="status" class="block text-black dark:text-white font-medium mb-2">Categoria</label>
          <select
            id="category_id"
            name="category_id"
            v-model="form.category_id"
            class="dark:bg-gray-950 dark:text-white w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 text-black"
            required
          >
            <option value="1" class="text-black dark:text-white">Hogar</option>
            <option value="2" class="text-black dark:text-white">Tecnologia</option>
            <option value="3" class="text-black dark:text-white">Deportes</option>
            <option value="4" class="text-black dark:text-white">Moda</option>
            <option value="5" class="text-black dark:text-white">Salud y belleza</option>
            <option value="6" class="text-black dark:text-white">Juguetes</option>
          </select>
        </div>

        <button
          type="submit"
          class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-[#006699] dark:to-[#004266] text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-300"
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

<style scoped>
.image-preview {
    max-width: 200px;
    margin-top: 10px;
}
</style>