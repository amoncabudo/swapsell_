<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { onMounted } from 'vue';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import { defineProps, ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Link, Head } from '@inertiajs/vue3';
import Cookies from "@/Components/Cookies.vue";
import { cartCount, incrementCartCount, decrementCartCount } from '@/cartState';

import axios from 'axios';
let comments = ref([]);
let props = defineProps({
  isAuthenticated: Boolean,
  product: Object,
  user: Object,
  commentarios: Array, // Ensure you are passing "comments"
  mediaReview: Number
});

onMounted(() => {
  if (props.commentarios) {
    comments.value = props.commentarios; // Assign the comments correctly
  } else {
    console.warn("Els comentaris no estan definits en les props.");
  }

  console.log("Comentaris en ref:", comments.value);
  console.log("Comentaris en props:", props.commentarios);
});



const submitData = () => {
  console.log("----")
  axios.post('/comentarios', {
    comment: form,
  })
    .then((response) => {
      comments.value = response.data; // Update the comments

      // Clear error message if successful
    })
    .catch((error) => {
      // Error handling

      console.error('Error al enviar dades:', error);

    });
};

console.log(props)

let map;
let form = useForm({
  description: "",
  id_product: props.product.id,
  message: "",
});

onMounted(() => {
  // Initialize and set the map
  map = L.map('map').setView([props.product.latitude, props.product.longitude], 13);

  // Add OpenStreetMap layer
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  // Create a custom icon
  const customIcon = L.icon({
    iconUrl: 'storage/app/public/product_marker_map.png',
    iconSize: [40, 40],
    iconAnchor: [20, 40],
    popupAnchor: [0, -40]
  });

  // Add marker with the product location
  L.marker([props.product.latitude, props.product.longitude], { icon: customIcon })
    .bindPopup(`<b>${props.product.name}</b><br>${props.product.price}€`)
    .addTo(map);
});
const calcularTiempo = (fecha) => {
  if (!fecha) return "Hace N/A"; // If the date is null, return "N/A"

  const fechaComentario = new Date(fecha);
  const fechaHoy = new Date();

  // Calculate the difference in milliseconds
  const diferenciaTiempo = fechaHoy - fechaComentario;

  // Calculate the difference in seconds, minutes, hours and days
  const segundosTranscurridos = Math.floor(diferenciaTiempo / 1000); // Calculate the difference in seconds
  const minutosTranscurridos = Math.floor(diferenciaTiempo / (1000 * 60)); // Calculate the difference in minutes
  const horasTranscurridas = Math.floor(diferenciaTiempo / (1000 * 60 * 60)); // Calculate the difference in hours
  const diasTranscurridos = Math.floor(diferenciaTiempo / (1000 * 60 * 60 * 24)); // Calculate the difference in days

  // If it is today, show the seconds, minutes or hours
  if (diasTranscurridos === 0) {
    if (horasTranscurridas > 0) {
      return `Hace ${horasTranscurridas} hora${horasTranscurridas === 1 ? '' : 's'}`; // If it is more than one hour, show the hours
    } else if (minutosTranscurridos > 0) {
      return `Hace ${minutosTranscurridos} minuto${minutosTranscurridos === 1 ? '' : 's'}`; // If it is more than one minute, show the minutes
    } else {
      return `Hace ${segundosTranscurridos} segundo${segundosTranscurridos === 1 ? '' : 's'}`; // If it is more than one second, show the seconds
    }
  }

  // If it is more than one day, show the days
  return `Hace ${diasTranscurridos} día${diasTranscurridos === 1 ? '' : 's'}`;
};

const calcularAntiguedad = (fecha) => {
  if (!fecha) return "Data no disponible"; // If the date is null, return "No disponible"

  try {
    const fechaCreacion = new Date(fecha);
    if (isNaN(fechaCreacion)) throw new Error("Data inválida"); // If the date is invalid, throw an error

    const ahora = new Date();
    const diferencia = ahora - fechaCreacion;

    const años = Math.floor(diferencia / (1000 * 60 * 60 * 24 * 365)); // Calculate the difference in years
    const meses = Math.floor(diferencia / (1000 * 60 * 60 * 24 * 30)) % 12; // Calculate the difference in months
    const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24)) % 30; // Calculate the difference in days
    const horas = Math.floor(diferencia / (1000 * 60 * 60)) % 24; // Calculate the difference in hours
    const minutos = Math.floor(diferencia / (1000 * 60)) % 60; // Calculate the difference in minutes

    if (años > 0) return `fa ${años} anys${años > 1 ? "s" : ""}`; // If it is more than one year, show the years
    if (meses > 0) return `fa ${meses} mesos${meses > 1 ? "es" : ""}`; // If it is more than one month, show the months
    if (dias > 0) return `fa ${dias} dies${dias > 1 ? "s" : ""}`; // If it is more than one day, show the days
    if (horas > 0) return `fa ${horas} hores${horas > 1 ? "s" : ""}`; // If it is more than one hour, show the hours
    return `fa ${minutos} minuts${minutos > 1 ? "s" : ""}`; // If it is more than one minute, show the minutes
  } catch (error) {
    console.error("Error al calcular l'antiguitat:", error); // If the date is invalid, show an error
    return "Data no vàlida"; // If the date is invalid, show "Data no vàlida"
  }
};

// Computed para actualizar automáticamente si cambia el `user.created_at`
const miembroDesde = computed(() => props.user?.created_at ? calcularAntiguedad(props.user.created_at) : "Cargando...");

console.log(miembroDesde);

console.log(props.user.created_at);

function toggleBasket(product) {
  axios.post(route('baskets_products'), { id: product.id })
    .then(response => {
      product.is_basket = response.data.is_basket; // Update the basket status based on the server response
      if (product.is_basket) {
        incrementCartCount(); // Increment the cart count
      } else {
        decrementCartCount(); // Decrement the cart count
      }
    })
    .catch(error => {
      console.error("Error al actualizar l'estat de favorits:", error); // If the basket status is invalid, show an error
    });
}

const isBasket = (product) => {
  return product.is_basket; // Return the basket status
};

</script>

<template>

  <Head title="Producte Ampliat"></Head>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-12 text-black">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main container with glass effect -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 mb-8">
          <div class="flex flex-col lg:flex-row gap-8">

            <!-- Section of image and buttons -->
            <div class="w-full lg:w-1/2">
              <div class="bg-white rounded-xl shadow-lg p-4">
                <h1 class="text-2xl font-bold text-black mb-4 text-center">{{ product.name }}</h1>
                <div class="relative group">
                  <img :src="`/storage/${product.image}`" :alt="product.name"
                    class="w-full h-[286px] object-cover rounded-lg transition-transform duration-300 group-hover:scale-105">
                  <div
                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                  </div>
                </div>

                <!-- Buttons of action -->
                <div class="flex justify-center space-x-4 mt-6">
                  <button v-if="!product.bid" @click="toggleBasket(product)"
                    class="flex items-center space-x-2 bg-SubastaButton1 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>{{ isBasket(product) ? 'Eliminar del carret' : 'Comprar ara' }}</span>
                  </button>
                  <Link :href="route('auction')" class="text-white">
                  <button
                    class="flex items-center space-x-2 bg-amber-700 text-white px-6 py-3 rounded-lg hover:bg-amber-500 transition-all duration-300 transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    <span>Subastar</span>

                  </button>
                  </Link>
                </div>
              </div>
            </div>

            <!-- Section of information -->
            <div class="w-full lg:w-1/2 space-y-6">
              <!-- Information of the seller -->
              <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Informació del venedor</h2>
                <div class="flex items-center space-x-4">
                  <img :src="`/${user.image}`" alt="User" class="w-16 h-16 rounded-full border-2 border-gray-200">
                  <div>
                    <p class="font-semibold text-lg">{{ props.user.name }} {{ props.user.surname }}</p>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                      <span>⭐ {{ props.mediaReview }}</span>
                      <span>•</span>
                      <span>Membre desde</span><span>{{ miembroDesde }}</span>

                    </div>
                  </div>
                </div>
              </div>

              <!-- Details of the product -->
              <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Detalls del producte</h2>
                <div class="space-y-4">
                  <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-black">Nom:</span>
                    <span class="font-semibold text-black">{{ product.name }}</span>
                  </div>
                  <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-black">Preu:</span>
                    <span class="font-semibold text-xl text-green-600">{{ product.price }}€</span>
                  </div>
                  <div class="flex justify-between items-center py-2">
                    <span class="text-black">Descripció:</span>
                    <span class="text-black">{{ product.description }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- New section of comments -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 mt-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-6">Comentaris</h2>

          <!-- Form for new comment -->
          <div class="mb-8">
            <div class="flex items-start space-x-4">
              <img :src="`/${user.image}`" alt="User Avatar" class="w-10 h-10 rounded-full">
              <div class="flex-1">
                <textarea aria-label="Comments TextArea"
                  class="w-full p-4 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black focus:border-blue-500"
                  placeholder="Escriu el teu comentari..." rows="3" id="description" name="description" required
                  v-model="form.message"></textarea>
                <button type="submit" @click="submitData"
                  class="mt-2 bg-SubastaButton1 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-all duration-300">
                  Publicar comentari
                </button>

              </div>
            </div>
          </div>
          <!-- List of comments -->
          <div class="space-y-6">
            <!-- Comments with styled scroll -->
            <div class="max-h-[300px] overflow-y-auto pr-4 space-y-6 custom-scrollbar">
              <div v-for="(comentario, i) in comments" :key="i" class="flex items-start space-x-4">
                <img src="/images/User.png" alt="User" class="w-10 h-10 rounded-full">
                <div class="flex-1">
                  <div
                    class="bg-white/80 backdrop-blur-sm rounded-lg p-4 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-center mb-2">
                      <p class="font-semibold text-gray-800">{{ comentario.user.name }} </p>
                      <span class="text-sm text-gray-500">{{ comentario.tiempo_transcurrido }}</span>
                    </div>
                    <p class="text-black"> {{ comentario.description }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 mt-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-6">Ubicació del producte</h2>
          <div id="map" class="h-[400px] w-full rounded-lg"></div>
        </div>
      </div>
    </div>
  </component>
  <Cookies />
</template>

<style scoped>
.glass-effect {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 8px 32px 0 rgba(0, 66, 102, 0.2);
}

/* Add styles for the map */
#map {
  z-index: 1;
}

/* Styling the scrollbar */
.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.4);
  border-radius: 10px;
  margin: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #004266;
  border-radius: 10px;
  border: 2px solid rgba(255, 255, 255, 0.4);
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #003252;
}

/* Support for Firefox */
.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: #004266 rgba(255, 255, 255, 0.4);
}
</style>
