<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { Head } from '@inertiajs/vue3';
import Cookies from "@/Components/Cookies.vue";

const props = defineProps({
    isAuthenticated: Boolean
});

let form = useForm({
  title: "",
  description: "",
  date: null,
  time: "active",
  longitude: null,
  latitude: null,
  image: null
});

function handleFileUpload(event) {
  form.image = event.target.files[0];
}
</script>

<template>
  <Head title="Crea un Esdeveniment"></Head>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-[#1b2e37] dark:to-[#224466] py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
          <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">Crea un Esdeveniment</h1>
          <p class="text-gray-600 dark:text-white">Completa els detalls del teu esdeveniment:</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
          <form @submit.prevent="form.post(route('events'))" class="space-y-6">
            <div>
              <label for="title" class="block text-sm font-medium text-gray-700 dark:text-white">Nom de l'esdeveniment</label>
              <input type="text" id="title" v-model="form.title" class=" dark:text-white dark:bg-gray-950 w-full mt-1 p-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required />
            </div>

            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 dark:text-white">Descripció</label>
              <textarea id="description" v-model="form.description" rows="4" class="dark:text-white dark:bg-gray-950 w-full mt-1 p-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
            </div>

            <div>
              <label for="date" class="block text-sm font-medium text-gray-700 dark:text-white">Data</label>
              <input type="date" id="date" v-model="form.date" class="dark:text-white dark:bg-gray-950 w-full mt-1 p-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required />
            </div>

            <div>
              <label for="time" class="block text-sm font-medium text-gray-700 dark:text-white">Hora</label>
              <input type="time" id="time" v-model="form.time" class="dark:text-white dark:bg-gray-950 w-full mt-1 p-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required />
            </div>

            <div>
              <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-white">Latitud</label>
              <input type="number" step="any" id="latitude" v-model="form.latitude" class="dark:text-white dark:bg-gray-950 w-full mt-1 p-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required />
            </div>

            <div>
              <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-white">Longitud</label>
              <input type="number" step="any" id="longitude" v-model="form.longitude" class="dark:text-white dark:bg-gray-950 w-full mt-1 p-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required />
            </div>

            <div>
              <label for="image" class="block text-sm font-medium text-gray-700 dark:text-white">Imatge de l'esdeveniment</label>
              <input type="file" id="image" @change="handleFileUpload" class=" dark:text-white w-full mt-1 p-3rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required />
            </div>

            <div class="mt-8">
              <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-[#006699] dark:to-[#004266] text-white font-bold py-3 px-4 rounded-xl hover:from-blue-600 hover:to-indigo-700 transform hover:-translate-y-0.5 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Publicar Esdeveniment
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </component>
  <Cookies />
</template>
