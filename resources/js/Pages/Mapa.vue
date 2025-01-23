<template>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <!-- Sección del mapa -->
    <div id="map-container">
      <!-- Botón accesible para enfocar el mapa -->
      

      <!-- Mapa interactivo con atributos de accesibilidad -->
      <div
        id="map"
        role="region"
        aria-label="Mapa interactivo mostrando ubicaciones de productos"
        tabindex="0"
      ></div>
    </div>
  </component>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { onMounted, nextTick, ref } from 'vue';

import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const map = ref(null);
const products = ref([]);
const isAuthenticated = ref(false); // Define el valor inicial

// Función para enfocar el mapa cuando el botón es presionado
const focusMap = () => {
  const mapElement = document.getElementById('map');
  if (mapElement) {
    mapElement.focus();
  }
};

// Inicializa el mapa
const initializeMap = () => {
  map.value = L.map('map').setView([42.265507, 2.958105], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map.value);
  console.log('Mapa inicializado:', map.value);
};

// Carga productos desde el backend y agrega marcadores
const loadProducts = async () => {
  try {
    const response = await axios.get('/api/products');
    products.value = response.data;
    console.log('Productos cargados:', products.value);

    products.value.forEach((product) => {
      L.marker([product.latitude, product.longitude])
        .addTo(map.value)
        .bindPopup(`<strong>${product.name}</strong><br>${product.description || ''}`)
        .getElement()
        .setAttribute(
          'aria-label',
          `Ubicación de ${product.name}: ${product.description || 'sin descripción'}`
        );
    });
  } catch (error) {
    console.error('Error al cargar los productos:', error);
  }
};

// Ejecuta la inicialización al montar el componente
onMounted(async () => {
  await nextTick();
  initializeMap();
  await loadProducts();
});
</script>

<style>
#map-container {
  position: relative;
  height: 100vh;
  width: 100vw;
  margin: 0;
}

/* Botón para acceder al mapa */
#focus-map-button {
  position: absolute;
  top: 10px;
  left: 10px;
  z-index: 1000;
  background-color: #007bff;
  color: #fff;
  border: none;
  padding: 10px 15px;
  font-size: 14px;
  border-radius: 4px;
  cursor: pointer;
}

#focus-map-button:focus,
#focus-map-button:hover {
  background-color: #0056b3;
}

#map {
  height: 100%;
  width: 100%;
  outline: none; /* Permite que sea navegable sin mostrar bordes innecesarios */
}

body,
html {
  margin: 0;
  padding: 0;
  overflow: hidden;
}
</style>
