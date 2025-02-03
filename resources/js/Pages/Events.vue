<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Card from 'primevue/card';
import Button from 'primevue/button';
import Paginator from 'primevue/paginator';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';

const props = defineProps({
    events: Array,
    isAuthenticated: Boolean   
});
console.log(props.events);

// Pagination state
const first = ref(0);
const rows = ref(5);
const totalRecords = ref(15);



const navigateToEvent = (event) => {
    window.location.href = `/EventInfo/${event.id}`;
};


</script>
<template>
    <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="min-h-screen bg-gray-50 p-8">
        <h1 class="text-4xl font-bold text-center mb-12 text-black">ESDEVENIMENTS</h1>

        <!-- Events List -->
        <div v-if="events.length" class="max-w-5xl mx-auto space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="event in events" :key="event.id" 
               class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="relative">
              <img :src="`/uploads/events/${event.image}`" 
                   :alt="event.name" 
                   class="w-full h-56 object-cover">
            </div>
            <div class="p-6">
              
                <h2 class="text-xl font-semibold text-gray-900 mb-3 font-bold">{{ event.title }}</h2>
                <p class="text-gray-800 text-base mb-4 line-clamp-2">{{ event.description }}</p>
                <div class="flex justify-between items-center mb-4">
                  <span class="text-black font-bold">Data: {{ event.date }}</span>
                  <span class="text-black font-bold">Hora: {{ event.time }}h</span>
                </div>
                <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                  <div class="flex items-center">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <span class="text-gray-800">{{ event.location || 'Ubicación no disponible' }}</span>
                    <span class="mx-2 text-gray-800">•</span>
                    <span class="text-gray-800">Hace 2h</span>
                  </div>
                  <button class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                  </button>
                </div>
                
                <div class="flex justify-between mt-5">
                <Link :href="route('event.show', event.id)" class="block"><button class="bg-custom-blue text-white p-2 rounded">Editar</button></Link>
                <Link :href="route('deleteEvent', event.id)" class="block"><button class="bg-red-800 text-white p-2 rounded">Eliminar</button></Link>
                </div>
              
            </div>
          </div>
        </div>
        </div>
        <div v-else class="text-center text-gray-600 text-xl font-semibold mt-20">
                No hay eventos disponibles
            </div>
            <Link href="/addEvent" 
            class="fixed bottom-8 right-8 bg-custom-blue text-white rounded-full p-4 shadow-lg transition-all duration-300 transform hover:-translate-y-1 hover:bg-custom-blue-dark">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
      </Link>
        </div>
   
    
    </component>
</template>

<style scoped>
.bg-custom-blue {
    background-color: #004266;
}

.event-card {
    @apply bg-gray-100 hover:shadow-md transition-shadow duration-300;
}

:deep(.p-card) {
    background-color: #f3f4f6;
    border: none;
    border-radius: 0.5rem;
}

:deep(.p-card .p-card-content) {
    padding: 0;
}

:deep(.p-button.p-button-text.p-button-info) {
    color: #1e40af;
    padding: 0.5rem 1rem;
}

:deep(.p-button.p-button-text.p-button-info:hover) {
    background: rgba(30, 64, 175, 0.1);
}

:deep(.p-paginator) {
    background: transparent;
    border: none;
}

:deep(.p-paginator .p-paginator-pages .p-paginator-page.p-highlight) {
    background: #1e40af;
    color: white;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .grid-cols-3 {
        @apply grid-cols-1 gap-4;
    }
    
    :deep(.p-card .p-card-content) {
        padding: 1rem;
    }
}
</style>