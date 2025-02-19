<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { onMounted } from 'vue';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import { defineProps, ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  events: Object,
  isAuthenticated: Boolean,
});

let map;

onMounted(() => {
  map = L.map('map').setView([props.events.latitude, props.events.longitude], 13); //Start map

  // Add OpenStreetMap layer
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  // Create custom icon
  const customIcon = L.icon({
    iconUrl: 'storage/app/public/product_marker_map.png',
    iconSize: [40, 40],
    iconAnchor: [20, 40],
    popupAnchor: [0, -40]
  });

  // Add marker with the event location
  L.marker([props.events.latitude, props.events.longitude], { icon: customIcon })
    .bindPopup(`<b>${props.events.title}</b><br>${props.events.time}H`)
    .addTo(map);
});
</script>

<template>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="min-h-screen from-indigo-100 to-blue-200 dark:from-[#1b2e37] dark:to-[#224466] py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main container with white background and shadows -->
        <div class="bg-white/90 dark:bg-gray-900 backdrop-blur-md rounded-2xl shadow-xl p-8 mb-8">
          <div class="flex flex-col lg:flex-row gap-8">
            <!-- Event image -->
            <div class="w-full lg:w-1/2">
              <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white mb-4 text-center">{{ events.title }}</h1>
                <div class="relative group">
                  <img :src="`/storage/${events.image}`" :alt="events.title"
                    class="w-full h-[300px] object-cover rounded-xl transition-transform duration-300 group-hover:scale-105">
                  <div
                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl"></div>
                </div>
              </div>
            </div>

            <!-- Event details -->
            <div class="w-full lg:w-1/2">
              <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-3xl font-semibold text-gray-800 dark:text-white mb-4">Event details</h2>
                <div class="space-y-4">
                  <div class="flex justify-between items-center py-2 border-b border-gray-300">
                    <span class="text-gray-600 dark:text-white">Nom:</span>
                    <span class="font-semibold text-gray-800 dark:text-white">{{ events.title }}</span>
                  </div>
                  <div class="flex justify-between items-center py-2 border-b border-gray-300">
                    <span class="mt-6 text-gray-600 dark:text-white">Descripció:</span>
                    <span class="text-gray-800 dark:text-white">{{ events.description }}</span>
                  </div>
                  <div class="flex justify-between items-center py-2 border-b border-gray-300">
                    <span class="mt-6 text-gray-600 dark:text-white">Data:</span>
                    <span class="text-gray-800 dark:text-white">{{ events.date }}</span>
                  </div>
                  <div class="flex justify-between items-center py-2">
                    <span class="mt-6 text-gray-600 dark:text-white">Hora:</span>
                    <span class="font-bold text-gray-800 dark:text-white">{{ events.time }}h</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-white/80 dark:bg-gray-900 backdrop-blur-sm rounded-2xl shadow-xl p-8 mt-8">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Ubicació de l'esdeveniment</h2>
        <div id="map" class="h-[400px] w-full rounded-lg"></div>
      </div>
      </div>
    </div>
  </component>
</template>
