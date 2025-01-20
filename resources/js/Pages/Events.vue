
<script setup>
import { ref } from 'vue';
import Card from 'primevue/card';
import Button from 'primevue/button';
import Paginator from 'primevue/paginator';

// Pagination state
const first = ref(0);
const rows = ref(5);
const totalRecords = ref(15);

// Events data
const events = ref([
    {
        id: 1,
        title: 'TÍTOL ESDEVENIMENT',
        location: 'Ubicació',
        date: 'Data de l\'esdeveniment',
        type: 'Tipus d\'esdeveniment'
    },
    {
        id: 2,
        title: 'TÍTOL ESDEVENIMENT',
        location: 'Ubicació',
        date: 'Data de l\'esdeveniment',
        type: 'Tipus d\'esdeveniment'
    },
    {
        id: 3,
        title: 'TÍTOL ESDEVENIMENT',
        location: 'Ubicació',
        date: 'Data de l\'esdeveniment',
        type: 'Tipus d\'esdeveniment'
    }
]);



const navigateToEvent = (event) => {
    window.location.href = `/EventInfo/${event.id}`;
};
</script>
<template>
    <div class="min-h-screen bg-gray-50 p-8">
        <h1 class="text-4xl font-bold text-center mb-12 text-black">ESDEVENIMENTS</h1>

        <!-- Events List -->
        <div class="max-w-5xl mx-auto space-y-4">
            <Card v-for="event in events" :key="event.id" class="event-card">
                <template #content>
                    <div class="p-2">
                        <h2 class="text-xl font-bold mb-4 text-black">{{ event.title }}</h2>
                        <div class="grid grid-cols-3 gap-8">
                            <div class="flex items-center space-x-2">
                                <i class="pi pi-map-marker text-blue-800"></i>
                                <span class="text-black">{{ event.location }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="pi pi-calendar text-blue-800"></i>
                                <span class="text-black">{{ event.date }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <i class="pi pi-tag text-blue-800"></i>
                                    <span class="text-black">{{ event.type }}</span>
                                </div>
                                <Button 
                                    label="VER MÁS" 
                                    class="p-button-text p-button-info"
                                    @click="navigateToEvent(event)"
                                />
                            </div>
                        </div>
                    </div>
                </template>
            </Card>
        </div>
    </div>
</template>

<style scoped>
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
  