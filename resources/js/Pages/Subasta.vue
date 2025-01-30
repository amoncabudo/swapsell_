<script setup>
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { ref, onMounted, onUnmounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    isAuthenticated: Boolean,
    products: Array
});

const countdowns = ref({});

const updateCountdown = (auctionId, totalSeconds) => {
    if (totalSeconds <= 0) {
        return '¡Subasta finalizada!';
    }

    const days = Math.floor(totalSeconds / (24 * 60 * 60));
    const hours = Math.floor((totalSeconds % (24 * 60 * 60)) / (60 * 60));
    const minutes = Math.floor((totalSeconds % (60 * 60)) / 60);
    const seconds = Math.floor(totalSeconds % 60);

    return `${days}d ${hours}h ${minutes}m ${seconds}s`;
};

// Start countdown timers
onMounted(() => {
    if (props.products) {
        props.products.forEach(product => {
            if (product.auction?.remaining?.total_seconds) {
                let remainingSeconds = product.auction.remaining.total_seconds;
                countdowns.value[product.auction.id] = remainingSeconds;

                const timer = setInterval(() => {
                    if (countdowns.value[product.auction.id] > 0) {
                        countdowns.value[product.auction.id]--;
                    } else {
                        clearInterval(timer);
                    }
                }, 1000);
            }
        });
    }
});

// Cleanup timers
onUnmounted(() => {
    Object.keys(countdowns.value).forEach(auctionId => {
        clearInterval(countdowns.value[auctionId]);
    });
});

const form = useForm({
    bid_amount: null,
    auction_id: null
});

const placeBid = (product) => {
    if (!product.bidPrice || product.bidPrice <= product.auction.current_price) {
        alert('La puja debe ser mayor al precio actual');
        return;
    }

    form.bid_amount = product.bidPrice;
    form.auction_id = product.auction.id;

    form.post(route('auctions.bid'), {
        preserveScroll: true,
        onSuccess: () => {
            // Actualizar el precio actual en la interfaz
            product.auction.current_price = product.bidPrice;
            product.bidPrice = null; // Limpiar el campo de puja
        },
        onError: (errors) => {
            alert('Error al realizar la puja: ' + Object.values(errors).join('\n'));
        }
    });
};
</script>

<template>
    <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Section with Wave Effect -->
                <div class="text-center mb-12 relative overflow-hidden p-8 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 shadow-xl">
                    <div class="relative z-10">
                        <h1 class="text-4xl font-bold text-white mb-4">Subastas Activas</h1>
                        <p class="text-lg text-white/90">Descubre productos únicos y participa en emocionantes subastas</p>
                        <Link 
                            v-if="isAuthenticated"
                            :href="route('auctions.create')" 
                            class="mt-6 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-blue-600 bg-white hover:bg-blue-50 transition-all transform hover:scale-105 shadow-md"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Crear Nueva Subasta
                        </Link>
                    </div>
                    <!-- Animated Wave Background -->
                    <div class="absolute inset-0 z-0 opacity-20">
                        <div class="wave"></div>
                    </div>
                </div>

                <!-- Grid de Subastas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="product in products" :key="product.id" 
                         class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl transform hover:-translate-y-2">
                        <!-- Imagen y Badge -->
                        <div class="relative">
                            <img 
                                :src="product.image || '/default-product.jpg'" 
                                :alt="product.name"
                                class="w-full h-64 object-cover"
                            >
                            <div class="absolute top-4 right-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-600 text-white shadow-lg">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Subasta Activa
                                </span>
                            </div>
                        </div>

                        <!-- Información de la subasta -->
                        <div class="p-6 space-y-4">
                            <h3 class="text-xl font-bold text-gray-900">{{ product.name }}</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <p class="text-sm text-blue-600 font-medium">Precio actual</p>
                                    <p class="text-lg font-bold text-blue-900">{{ product.auction?.current_price }}€</p>
                                </div>
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <p class="text-sm text-green-600 font-medium">Precio inicial</p>
                                    <p class="text-lg font-bold text-green-900">{{ product.auction?.start_price }}€</p>
                                </div>
                            </div>

                            <!-- Countdown Timer -->
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-600 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Tiempo restante
                                </p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ product.auction?.id && countdowns[product.auction.id] !== undefined 
                                        ? updateCountdown(product.auction.id, countdowns[product.auction.id])
                                        : 'Calculando...' 
                                    }}
                                </p>
                            </div>

                            <!-- Campo para ingresar el precio de la puja -->
                            <div class="mt-4">
                                <input 
                                    type="number" 
                                    v-model="product.bidPrice" 
                                    :min="product.auction?.current_price + 1"
                                    :placeholder="`Mínimo ${(product.auction?.current_price + 1)}€`"
                                    class="border rounded-lg p-2 w-full"
                                    :class="{'border-red-500': product.bidPrice <= product.auction?.current_price}"
                                />
                                <p v-if="product.bidPrice <= product.auction?.current_price" class="text-red-500 text-sm mt-1">
                                    La puja debe ser mayor al precio actual
                                </p>
                            </div>

                            <!-- Botones de acción -->
                            <div class="grid grid-cols-2 gap-4 pt-4">
                                <Link 
                                    :href="route('product.show', product.id)"
                                    class="inline-flex justify-center items-center px-4 py-2 border border-blue-600 text-sm font-medium rounded-lg text-blue-600 bg-white hover:bg-blue-50 transition-all"
                                >
                                    Ver Detalles
                                </Link>
                                <button 
                                    v-if="isAuthenticated"
                                    @click="placeBid(product)"
                                    :disabled="!product.bidPrice || product.bidPrice <= product.auction?.current_price"
                                    class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    Pujar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="products.length === 0" class="text-center py-12 bg-white rounded-2xl shadow-lg">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay subastas activas</h3>
                    <p class="mt-1 text-sm text-gray-500">Vuelve más tarde para ver nuevas subastas</p>
                </div>
            </div>
        </div>
    </component>
</template>

<style scoped>


@keyframes wave {
    0% { transform: translateX(-50%) rotateZ(0deg); }
    50% { transform: translateX(-25%) rotateZ(180deg); }
    100% { transform: translateX(-50%) rotateZ(360deg); }
}

/* Hover Effects */
.transform {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

.hover\:scale-105:hover {
    transform: scale(1.05);
}

.hover\:-translate-y-2:hover {
    transform: translateY(-0.5rem);
}

/* Add this to your existing styles */
.countdown-expired {
    color: #EF4444;
}
</style>