<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import ProgressSpinner from 'primevue/progressspinner';
import Cookies from "@/Components/Cookies.vue";

defineProps({
  isAuthenticated: Boolean,
});

let form = useForm({
  name: "",
  description: "",
  price: null,
  longitude: null,
  latitude: null,
  category_id: "",
  image: null
});

const generatedDescription = ref('');
const loading = ref(false);

function handleFileUpload(event) {
  form.image = event.target.files[0];
}

const generateDescription = async () => { // Generate the description of the product
  loading.value = true; // Set the loading to true
  try {
    const prompt = "Genera una descripción para un producto con el siguiente nombre: " + form.name; // Generate the prompt
    const response = await axios.post('http://localhost:11434/api/generate', { // Generate the response
      model: 'deepseek-r1', // Model
      prompt: prompt, // Prompt
      stream: false, // Stream
    });

    let text = response.data.response; // Get the text
    text = text.replace(/<think>[\s\S]*?<\/think>/g, ''); // Remove the think
    text = text.replace(/\*/g, ''); // Remove the *
    generatedDescription.value = text; // Set the generated description
    form.description = text; // Set the description

    console.log(generatedDescription.value); // Log the generated description

  } catch (error) {
    console.error("Error al generar la descripció:", error);
    alert("Hi ha hagut un error al generar la descripció. Per favor, torna a provar.");
  } finally {
    loading.value = false; // Set the loading to false
  }
};

</script>

<template>
  <Head title="Vendre"></Head>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 dark:from-[#1b2e37] dark:to-[#224466] to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-2xl mx-auto">
        <!-- Header of the form -->
        <div class="text-center mb-8">
          <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">
            Publica el teu Producte
          </h1>
          <p class="text-gray-600 dark:text-white">
            Completa els detalls del teu producte:
          </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
          <form @submit.prevent="form.post(route('sell'))" enctype="multipart/form-data" class="space-y-6">
            <!-- Name field -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-white">Nom del Producte</label>
              <div class="relative mt-1"> 
                <input type="text" id="name" v-model="form.name" placeholder="Nom del producte"class="dark:bg-black dark:text-white dark:placeholder-gray-200 block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Description field -->
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 dark:text-white">Descripció</label>
              <div class="relative mt-1">
                <textarea id="description" v-model="form.description" rows="4" placeholder="Afegeix una descripció"class="dark:placeholder-gray-200 dark:text-white dark:bg-black block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required></textarea>
                <ProgressSpinner v-if="loading" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-15 h-15" />
              </div>
              <button type="button" @click="generateDescription" class="mt-2 w-full bg-blue-500 dark:bg-gray-950 text-white font-bold py-2 rounded hover:bg-blue-600">
                Generar Descripció
              </button>
            </div>

            <!-- Image field -->
            <div>
              <label for="image" class="block text-sm font-medium text-gray-700 dark:text-white">Imatge del Producte</label>
              <div class="relative mt-1">
                <input type="file" id="image" @change="handleFileUpload" class="dark:text-white block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required />
              </div>
            </div>

            <!-- Price field -->
            <div>
              <label for="price" class="block text-sm font-medium text-gray-700 dark:text-white">Preu (€)</label>
              <div class="relative mt-1">
                <input type="number" id="price" v-model="form.price" placeholder="Preu aproximat" class="dark:placeholder-gray-200 dark:text-white dark:bg-black block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Location fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-white">Latitud</label>
                <div class="relative mt-1">
                  <input type="number" step="any" id="latitude" v-model="form.latitude" placeholder="Afageix una latitud"class="dark:placeholder-gray-200 dark:text-white dark:bg-black block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                  <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                  </span>
                </div>
              </div>
              
              <div>
                <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-white">Longitud</label>
                <div class="relative mt-1">
                  <input type="number" step="any" id="longitude" v-model="form.longitude" placeholder="Afageix una latitud" class="dark:placeholder-gray-200 dark:text-white dark:bg-black block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                  <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                  </span>
                </div>
              </div>
            </div>

            <!-- Category field -->
            <div>
              <label for="category" class="block text-sm font-medium text-gray-700 dark:text-white">Categoria</label>
              <div class="relative mt-1">
                <select id="category_id" v-model="form.category_id" class="dark:placeholder-gray-200 dark:text-white dark:bg-black block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                  <option value="1">Llar</option>
                  <option value="2">Tecnologia</option>
                  <option value="3">Esports</option>
                  <option value="4">Moda</option>
                  <option value="5">Salut i Bellesa</option>
                  <option value="6">Joguines</option>
                </select>
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Submit button -->
            <div class="mt-8">
              <button type="submit" class="dark:from-[#006699] to-[#004266] w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold py-3 px-4 rounded-xl hover:from-blue-600 hover:to-indigo-700 transform hover:-translate-y-0.5 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Publicar Producte
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </component>
  <Cookies />
</template>