<template>
    <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
     
       <!-- Sección del complemento -->
       
 
       <!-- Sección del mapa -->
       <div id="map"></div>
     
    </component>
   </template>
   
   <script setup>
   import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
   import NavbarS from '@/Layouts/NavbarS.vue';
import { onMounted, nextTick, ref } from 'vue';
import axios from 'axios'; // Asegúrate de instalar axios
import L from 'leaflet'; // Importa Leaflet
import 'leaflet/dist/leaflet.css'; // Importa estilos de Leaflet

// Referencias
const map = ref(null); // Referencia del mapa
const products = ref([]); // Para almacenar los productos existentes

// Inicializa el mapa
const initializeMap = () => {
  map.value = L.map('map').setView([42.265507, 2.958105], 13); // Centro inicial
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map.value);
};

// Carga productos desde el backend y agrega marcadores
const loadProducts = async () => {
  try {
    const response = await axios.get('/api/products'); // Asegúrate de tener este endpoint configurado
    products.value = response.data;

    // Agrega un marcador por cada producto
    products.value.forEach(products => {
      L.marker([products.latitude, products.longitude])
        .addTo(map.value)
        .bindPopup(`<strong>${products.name}</strong><br>${products.description || ''}`)
        .openPopup();
    });
  } catch (error) {
    console.error('Error al cargar los productos:', error);
  }
};

// Ejecuta la inicialización al montar el componente
onMounted(async () => {
  await nextTick();
  initializeMap();
  await loadProducts(); // Carga los productos y los muestra en el mapa
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