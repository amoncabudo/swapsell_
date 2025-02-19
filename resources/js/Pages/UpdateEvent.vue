<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { Head } from '@inertiajs/vue3';
import Cookies from "@/Components/Cookies.vue";

const props = defineProps({
  event: Object,
  isAuthenticated: Boolean
});

let form = useForm({
  id: props.event.id,
  title: props.event.title,
  description: props.event.description,
  date: props.event.date,
  time: props.event.time,
  longitude: props.event.longitude,
  latitude: props.event.latitude,
});
console.log(props.event);
</script>

<template>
  <Head title="Editar event" />
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-[#1b2e37] dark:to-[#224466] py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-2xl mx-auto">
        <!-- Header of the form -->
        <div class="text-center mb-8">
          <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">
            Edita el teu Event
          </h1>
          <p class="text-gray-600 dark:text-white">
            Edita els detalls del teu event per editar-lo
          </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
          <form @submit.prevent="form.post(route('updateEvent'))" class="space-y-6">
            <input type="number" id="id" name="id" v-model="form.id" hidden class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200" required />

            <!-- Title field -->
            <div>
              <label for="title" class="block text-sm font-medium text-gray-700 dark:text-white">Nom de l'event</label>
              <div class="relative mt-1">
                <input type="text" id="title" name="title" v-model="form.title" class="dark:bg-gray-950 dark:text-white block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                  <svg class="w-5 h-5 text-gray-400 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Description field -->
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 dark:text-white">Descripció</label>
              <div class="relative mt-1">
                <textarea id="description" v-model="form.description" rows="4" class="dark:bg-gray-950 dark:text-white block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required></textarea>
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                  <svg class="w-5 h-5 text-gray-400 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Date field -->
            <div>
              <label for="date" class="block text-sm font-medium text-gray-700 dark:text-white">Data</label>
              <div class="relative mt-1">
                <input type="date" id="date" v-model="form.date" class="dark:bg-gray-950 dark:text-white block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                  <svg class="w-5 h-5 text-gray-400 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Time field -->
            <div>
              <label for="time" class="block text-sm font-medium text-gray-700 dark:text-white">Hora</label>
              <div class="relative mt-1">
                <input type="time" id="time" v-model="form.time" class="dark:bg-gray-950 dark:text-white block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                  <svg class="w-5 h-5 text-gray-400 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Latitude field -->
            <div>
              <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-white">Latitud</label>
              <div class="relative mt-1">
                <input type="number" step="any" id="latitude" v-model="form.latitude" class="dark:bg-gray-950 dark:text-white block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                  <svg class="w-5 h-5 text-gray-400 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13a3 3 0 100-6 3 3 0 000 6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.8 13.938h-.011a7 7 0 10-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155z" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Longitude field -->
            <div>
              <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-white">Longitud</label>
              <div class="relative mt-1">
                <input type="number" step="any" id="longitude" v-model="form.longitude" class="dark:bg-gray-950 dark:text-white block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                  <svg class="w-5 h-5 text-gray-400 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13a3 3 0 100-6 3 3 0 000 6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.8 13.938h-.011a7 7 0 10-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155z" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Submit button -->
            <div class="mt-8">
              <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-[#006699] dark:to-[#004266] text-white font-bold py-3 px-4 rounded-xl hover:from-blue-600 hover:to-indigo-700 transform hover:-translate-y-0.5 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Publicar Producto
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </component>
  <Cookies />
</template>