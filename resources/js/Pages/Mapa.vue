<template>
    <AuthenticatedLayout>
     <div class="layout-container">
       <!-- Sección del complemento -->
       <div class="complement">
        
       </div>
 
       <!-- Sección del mapa -->
       <div id="map"></div>
     </div>
   </AuthenticatedLayout>
   </template>
   
   <script setup>
   import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
 
   import { onMounted, nextTick } from 'vue'; // nextTick asegura que el DOM esté disponible
   import L from 'leaflet'; // Importa Leaflet
   import 'leaflet/dist/leaflet.css'; // Importa estilos de Leaflet
   
   onMounted(async () => {
     
     await nextTick();
   
     // Inicializa el mapa centrado en una ubicación específica
     const map = L.map('map').setView([42.265507, 2.958105], 13); // Latitud, Longitud
   
     // Agrega la capa base de OpenStreetMap
     L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
       attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
     }).addTo(map);
   
     // Agrega un marcador en la ubicación inicial
     L.marker([42.265507, 2.958105])
       .addTo(map)
       .bindPopup('producto1')
       .openPopup();
   });
   </script>
   
   <style>
   
   #map {
     height: 100vh;
     width: 100vw;
     margin: 0;
   }
   body, html {
     margin: 0;
     padding: 0;
     overflow: hidden;
   }
   </style>
 
 