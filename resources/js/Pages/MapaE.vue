<template>
  <Head title = "Mapa event"></Head>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <!-- Sección del mapa -->
    <div id="map-container">
      <!-- Botón accesible para enfocar el mapa -->
      

      <!-- Mapa interactivo con atributos de accesibilidad -->
      <div
        id="map"
        role="region"
        aria-label="Mapa interactiu mostrant ubicacions de esdeveniments"
        tabindex="0"
      ></div>
    </div>
  </component>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavbarS from "@/Layouts/NavbarS.vue";
import { onMounted, ref } from "vue";
import { Head } from '@inertiajs/vue3';
import L from "leaflet";
import "leaflet/dist/leaflet.css";
let props = defineProps({
  events: Array,
  isAuthenticated: Boolean,
});


// Referencias y datos
const map = ref(null); // Referencia al mapa
let events = ref(props.events); // Productos cargados



// Inicializa el mapa
const initializeMap = () => {
  // Primero intentamos obtener la ubicación del usuario
  if ("geolocation" in navigator) {
    navigator.geolocation.getCurrentPosition(
      // Éxito en obtener la ubicación
      (position) => {
        const userLat = position.coords.latitude;
        const userLng = position.coords.longitude;
        initializeMapWithPosition(userLat, userLng);
      },
      // Error o rechazo de permisos
      (error) => {
        console.log("Error de geolocalització:", error);
        // Usar posición por defecto
        initializeMapWithPosition(42.265507, 2.958105);
      },
      // Opciones de geolocalización
      {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
      }
    );
  } else {
    // Navegador no soporta geolocalización
    console.log("Geolocalització no disponible");
    initializeMapWithPosition(42.265507, 2.958105);
  }
};

console.log(events.value);


const initializeMapWithPosition = (lat, lng) => {
  // Crear el mapa con la vista inicial
  map.value = L.map("map", {
    zoomControl: true,
    attributionControl: true,
  }).setView([lat, lng], 13);

  // Añadir la capa de tiles
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map.value);





  const locationButton = L.control({position: 'topright'});
  
  locationButton.onAdd = function(map) {
    const div = L.DomUtil.create('div', 'leaflet-bar mainbutton ');
    div.innerHTML = `
      <a href="#" title="Mostrar mi ubicación" role="button" aria-label="La meva ubicació" 
         style="width: 30px; height: 30px; line-height: 30px; text-align: center; display: block; 
                background: white; border-radius: 4px;">
        <svg viewBox="0 0 24 24" style="width: 18px; height: 18px; margin: 6px;">
          <path d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3c-.46-4.17-3.77-7.48-7.94-7.94V1h-2v2.06C6.83 3.52 3.52 6.83 3.06 11H1v2h2.06c.46 4.17 3.77 7.48 7.94 7.94V23h2v-2.06c4.17-.46 7.48-3.77 7.94-7.94H23v-2h-2.06zM12 19c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z"/>
        </svg>
      </a>
    `;

    div.onclick = function() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;
            map.setView([userLat, userLng], 15);
          },
          (error) => {
            console.error("Error getting location:", error);
            alert("No s'ha pogut obtenir la vostre ubicació");
          }
        );
      }
      return false;
    };

    return div;
  };

  locationButton.addTo(map.value);



  // Crear un grupo de marcadores
  const markersGroup = L.featureGroup();

  // Añadir marcador de ubicación actual si no es la posición por defecto
  if (lat !== 42.265507 || lng !== 2.958105) {
    L.marker([lat, lng], {
      icon: L.divIcon({
        className: 'user-location-marker',
        html: '<div style="background-color: #4285f4; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white;"></div>',
      })
    })
    .bindPopup('Ubicació actual')
    .addTo(map.value);
  }

  // Añadir los marcadores de productos al grupo
  events.value.forEach(element => {
    let contenido = `
    <p style="margin:0;font-weight:bold;">${element.title} </p> 
      <p style="margin:0;">${element.date}</p>
      <p style="margin:0;">${element.time}</p>
    <a  style="margin:0;" href="/productextend/${element.id}">Veure esdeveniment</a>
    `;
    L.marker([element.latitude, element.longitude], {icon: Icon})
      .bindPopup(contenido)
      .addTo(markersGroup);
  });

  // Añadir el grupo al mapa
  markersGroup.addTo(map.value);
};


console.log(events.value);


// Ejecuta al montar el componente
onMounted(() => {
  initializeMap(); // Inicializa el mapa
});

var Icon = L.icon({
  iconUrl: 'images/event_marker_map.png',
  

  iconSize:     [75, 75], // size of the icon
  
  iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
   
  popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
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

.user-location-marker {
  border: none;
  background: none;
}

.user-location-marker::after {
  content: '';
  position: absolute;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background-color: rgba(66, 133, 244, 0.2);
  top: -6px;
  left: -6px;
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% {
    transform: scale(1);
    opacity: 1;
  }
  100% {
    transform: scale(2);
    opacity: 0;
  }
}
.mainbutton {
  background-color: white;
  border-radius: 4px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
  cursor: pointer;
  display: block;
  height: 30px;
  line-height: 30px;
  margin: 10px;
  padding: 0;
  text-align: center;
  width: 30px;
  display: flex;
    align-items: center;
    justify-content: center;
}
</style>
