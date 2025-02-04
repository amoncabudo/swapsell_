<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { onMounted } from 'vue';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import { defineProps, ref , computed} from 'vue';
import { useForm } from '@inertiajs/vue3';


import axios from 'axios';
let comments = ref([]);
let props = defineProps({
  isAuthenticated: Boolean,
  product: Object,
  user: Object,
  commentarios: Array, // Asegúrate de que estás pasando "comments"
});

onMounted(() => {
  if (props.commentarios) {
    comments.value = props.commentarios; // Asignamos los comentarios correctamente
  } else {
    console.warn("Los comentarios no están definidos en las props.");
  }

  console.log("Comentarios en ref:", comments.value);
  console.log("Comentarios en props:", props.commentarios);
});



const submitData = () => {
  console.log("----")
  axios.post('/comentarios', {
    comment: form,
  })
    .then((response) => {
      // Manejo de éxito
      comments.value = response.data;

       // Limpiar mensaje de error si hay éxito 
    })
    .catch((error) => {
      // Manejo de error
      
      console.error('Error al enviar datos:', error);
      
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
  // Inicializar el mapa
  map = L.map('map').setView([props.product.latitude, props.product.longitude], 13);

  // Añadir capa de OpenStreetMap
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  // Crear icono personalizado
  const customIcon = L.icon({
    iconUrl: 'storage/app/public/product_marker_map.png',
    iconSize: [40, 40],
    iconAnchor: [20, 40],
    popupAnchor: [0, -40]
  });

  // Añadir marcador con la ubicación del producto
  L.marker([props.product.latitude, props.product.longitude], { icon: customIcon })
    .bindPopup(`<b>${props.product.name}</b><br>${props.product.price}€`)
    .addTo(map);
});
const calcularTiempo = (fecha) => {
  if (!fecha) return "Hace N/A"; // Si la fecha es nula, retorna "N/A"

  const fechaComentario = new Date(fecha);
  const fechaHoy = new Date();

  // Calcular la diferencia en milisegundos
  const diferenciaTiempo = fechaHoy - fechaComentario;

  // Calcular la diferencia en segundos, minutos, horas y días
  const segundosTranscurridos = Math.floor(diferenciaTiempo / 1000);
  const minutosTranscurridos = Math.floor(diferenciaTiempo / (1000 * 60));
  const horasTranscurridas = Math.floor(diferenciaTiempo / (1000 * 60 * 60));
  const diasTranscurridos = Math.floor(diferenciaTiempo / (1000 * 60 * 60 * 24));

  // Si es hoy, mostrar los segundos, minutos u horas
  if (diasTranscurridos === 0) {
    if (horasTranscurridas > 0) {
      return `Hace ${horasTranscurridas} hora${horasTranscurridas === 1 ? '' : 's'}`;
    } else if (minutosTranscurridos > 0) {
      return `Hace ${minutosTranscurridos} minuto${minutosTranscurridos === 1 ? '' : 's'}`;
    } else {
      return `Hace ${segundosTranscurridos} segundo${segundosTranscurridos === 1 ? '' : 's'}`;
    }
  }

  // Si es más de un día, mostrar los días
  return `Hace ${diasTranscurridos} día${diasTranscurridos === 1 ? '' : 's'}`;
};




const calcularAntiguedad = (fecha) => {
  if (!fecha) return "Fecha no disponible";

  try {
    const fechaCreacion = new Date(fecha);
    if (isNaN(fechaCreacion)) throw new Error("Fecha inválida");

    const ahora = new Date();
    const diferencia = ahora - fechaCreacion;

    const años = Math.floor(diferencia / (1000 * 60 * 60 * 24 * 365));
    const meses = Math.floor(diferencia / (1000 * 60 * 60 * 24 * 30)) % 12;
    const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24)) % 30;
    const horas = Math.floor(diferencia / (1000 * 60 * 60)) % 24;
    const minutos = Math.floor(diferencia / (1000 * 60)) % 60;

    if (años > 0) return `fa ${años} anys${años > 1 ? "s" : ""}`;
    if (meses > 0) return `fa ${meses} mesos${meses > 1 ? "es" : ""}`;
    if (dias > 0) return `fa ${dias} dies${dias > 1 ? "s" : ""}`;
    if (horas > 0) return `fa ${horas} hores${horas > 1 ? "s" : ""}`;
    return `fa ${minutos} minuts${minutos > 1 ? "s" : ""}`;
  } catch (error) {
    console.error("Error al calcular antigüedad:", error);
    return "Fecha no válida";
  }
};

// Computed para actualizar automáticamente si cambia el `user.created_at`
const miembroDesde = computed(() => props.user?.created_at ? calcularAntiguedad(props.user.created_at) : "Cargando...");

console.log(miembroDesde);

console.log(props.user.created_at);

</script>

<template>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Contenedor principal con efecto glass -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 mb-8">
          <div class="flex flex-col lg:flex-row gap-8">

            <!-- Sección de imagen y botones -->
            <div class="w-full lg:w-1/2">
              <div class="bg-white rounded-xl shadow-lg p-4">
                <h1 class="text-2xl font-bold text-gray-800 mb-4 text-center">{{ product.name }}</h1>
                <div class="relative group">
                  <img :src="`/storage/${product.image}`" :alt="product.name"
                    class="w-full h-[286px] object-cover rounded-lg transition-transform duration-300 group-hover:scale-105">
                  <div
                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                  </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-center space-x-4 mt-6">
                  <button
                    class="flex items-center space-x-2 bg-SubastaButton1 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Comprar ara</span>
                  </button>
                  <button
                    class="flex items-center space-x-2 bg-SubastaButton2 text-white px-6 py-3 rounded-lg hover:bg-amber-600 transition-all duration-300 transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Subastar</span>
                  </button>
                </div>
              </div>
            </div>

            <!-- Sección de información -->
            <div class="w-full lg:w-1/2 space-y-6">
              <!-- Información del vendedor -->
              <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Informació del venedor</h2>
                <div class="flex items-center space-x-4">
                  <img src="/images/User.png" alt="User" class="w-16 h-16 rounded-full border-2 border-gray-200">
                  <div>
                    <p class="font-semibold text-lg">{{ props.user.name }} {{ props.user.surname }}</p>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                      <span>⭐ 4.8</span>
                      <span>•</span>
                      <span>Membre desde</span><span>{{ miembroDesde }}</span>

                    </div>
                  </div>
                </div>
              </div>

              <!-- Detalles del producto -->
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

        <!-- Nueva sección de comentarios -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 mt-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-6">Comentaris</h2>

          <!-- Formulario para nuevo comentario -->
          <div class="mb-8">
            <div class="flex items-start space-x-4">
              <img :src="'/images/User.png'" alt="User Avatar" class="w-10 h-10 rounded-full">
              <div class="flex-1">
                <textarea 
                  class="w-full p-4 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black focus:border-blue-500"
                  placeholder="Escriu el teu comentari..."
                  rows="3" id="description" name="description" required v-model="form.message"
                ></textarea>
                <button type="submit" @click="submitData" class="mt-2 bg-SubastaButton1 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-all duration-300">
                  Publicar comentari
                </button>
                
              </div>
            </div>
          </div>
       


        <!-- Lista de comentarios -->
        <div class="space-y-6">
      <!-- Comentarios con scroll estilizado -->
      <div class="max-h-[300px] overflow-y-auto pr-4 space-y-6 custom-scrollbar">
              <div v-for="(comentario,i) in comments" :key="i" class="flex items-start space-x-4">
          <img src="/images/User.png" alt="User" class="w-10 h-10 rounded-full">
          <div class="flex-1">
            <div class="bg-white/80 backdrop-blur-sm rounded-lg p-4 shadow-sm hover:shadow-md transition-all duration-300">
              <div class="flex justify-between items-center mb-2">
                      <p class="font-semibold text-gray-800" >{{ comentario.user.name }} </p>
                      <span class="text-sm text-gray-500">{{ comentario.tiempo_transcurrido }}</span>
                    </div>
                    <p class="text-black" > {{ comentario.description }}</p>
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
</template>

<style scoped>
.glass-effect {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 8px 32px 0 rgba(0, 66, 102, 0.2);
}

/* Añadir estilos para el mapa */
#map {
  z-index: 1;
}

/* Estilización del scrollbar */
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

/* Soporte para Firefox */
.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: #004266 rgba(255, 255, 255, 0.4);
}
</style>
