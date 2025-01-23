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
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavbarS from "@/Layouts/NavbarS.vue";
import { onMounted, ref } from "vue";
import L from "leaflet";
import "leaflet/dist/leaflet.css";

// Referencias y datos
const map = ref(null); // Referencia al mapa
const products = ref([]); // Productos cargados
const isAuthenticated = ref(false); // Autenticación (puedes conectarlo al backend si es necesario)

// Inicializa el mapa
const initializeMap = () => {
  map.value = L.map("map", {
    zoomControl: true,
    attributionControl: true,
  }).setView([42.265507, 2.958105], 13); // Centro inicial del mapa

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map.value);
};

// Carga productos desde el backend (usando fetch)
const loadProducts = async () => {
  try {
    // Realiza la solicitud al backend
    const response = await fetch("/api/products"); // Cambia la URL si es necesario

    if (!response.ok) {
      throw new Error(`Error en la solicitud: ${response.status}`);
    }

    // Convierte la respuesta a JSON
    const data = await response.json();

    // Almacena los productos
    products.value = data.products || [];
    isAuthenticated.value = data.isAuthenticated || false;

    // Crea un marcador por cada producto
    products.value.forEach((product) => {
      if (products.latitude && products.longitude) {
        L.marker([products.latitude, products.longitude]) // Coordenadas
          .addTo(map.value) // Agrega el marcador al mapa
          .bindPopup(
            `<strong>${products.name}</strong><br>${products.description || "Sin descripción"}`
          ); // Popup con información
      }
    });
  } catch (error) {
    console.error("Error al cargar los productos:", error);
  }
};

// Ejecuta al montar el componente
onMounted(() => {
  initializeMap(); // Inicializa el mapa
  loadProducts(); // Carga productos
});
console.log(products.value);
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
