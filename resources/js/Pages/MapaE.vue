<template>
  <Head title = "Mapa event"></Head>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <!-- Map section -->
    <div id="map-container">
      <!-- Accessible button to focus the map -->
      

      <!-- Interactive map with accessibility attributes -->
      <div
        id="map"
        role="region"
        aria-label="Mapa interactiu mostrant ubicacions de esdeveniments"
        tabindex="0"
      ></div>
    </div>
  </component>
  <Cookies />
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavbarS from "@/Layouts/NavbarS.vue";
import { onMounted, ref } from "vue";
import { Head } from '@inertiajs/vue3';
import L from "leaflet";
import "leaflet/dist/leaflet.css";
import Cookies from "@/Components/Cookies.vue";

let props = defineProps({
  events: Array,
  isAuthenticated: Boolean,
});


// References and data
const map = ref(null); // Reference to the map
let events = ref(props.events); // Loaded events



// Initialize the map
const initializeMap = () => {
  // First we try to get the user's location
  if ("geolocation" in navigator) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const userLat = position.coords.latitude; // User latitude
        const userLng = position.coords.longitude; // User longitude
        initializeMapWithPosition(userLat, userLng);
      },
      // Error or permission rejection
      (error) => {
        console.log("Error de geolocalització:", error);
        // Use default position
        initializeMapWithPosition(42.265507, 2.958105);
      },
      // Geolocation options
      {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
      }
    );
  } else {
    // Browser does not support geolocation
    console.log("Navegador no soporta geolocalización");
    initializeMapWithPosition(42.265507, 2.958105);
  }
};


const initializeMapWithPosition = (lat, lng) => {
  // Create the map with the initial view
  map.value = L.map("map", {
    zoomControl: true, // Zoom control
    attributionControl: true, // Attribution control
  }).setView([lat, lng], 13); // Set the view to the initial position

  // Add the tiles layer
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map.value);

  // Add the location button
  const locationButton = L.control({position: 'topright'});
  
  locationButton.onAdd = function(map) {
    const div = L.DomUtil.create('div', 'leaflet-bar mainbutton '); // Create the location button
    div.innerHTML = `
      <a href="#" title="Mostrar mi ubicación" role="button" aria-label="La meva ubicació" 
         style="width: 30px; height: 30px; line-height: 30px; text-align: center; display: block; 
                background: white; border-radius: 4px;">
        <svg viewBox="0 0 24 24" style="width: 18px; height: 18px; margin: 6px;">
          <path d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3c-.46-4.17-3.77-7.48-7.94-7.94V1h-2v2.06C6.83 3.52 3.52 6.83 3.06 11H1v2h2.06c.46 4.17 3.77 7.48 7.94 7.94V23h2v-2.06c4.17-.46 7.48-3.77 7.94-7.94H23v-2h-2.06zM12 19c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z"/>
        </svg>
      </a>
    `;

    div.onclick = function() { // On click
      if (navigator.geolocation) { // If geolocation is supported
        navigator.geolocation.getCurrentPosition(
          (position) => {
            const userLat = position.coords.latitude; // User latitude
            const userLng = position.coords.longitude; // User longitude
            map.setView([userLat, userLng], 15); // Set the view to the user's location
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

  // Create a markers group
  const markersGroup = L.featureGroup();

  // Add the current location marker if it is not the default position
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

  // Add the products markers to the group
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

  // Add the group to the map
  markersGroup.addTo(map.value);
};

// Execute when the component is mounted
onMounted(() => {
  initializeMap(); // Initialize the map
});

var Icon = L.icon({
  iconUrl: 'images/event_marker_map.png', // Icon URL
  

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

/* Button to access the map */
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
  outline: none; /* Allows navigation without showing unnecessary borders */
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
