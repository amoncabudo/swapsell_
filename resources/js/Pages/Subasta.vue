<script setup>
import { Link, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { ref, onMounted, onUnmounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import jsPDF from 'jspdf';
import Cookies from "@/Components/Cookies.vue";
import { DialogDescription } from '@headlessui/vue';

const props = defineProps({
    isAuthenticated: Boolean,
    products: Array
});

const formatTime = (totalSeconds) => {
    if (totalSeconds <= 0) return { days: 0, hours: 0, minutes: 0, seconds: 0 }; // If the total seconds are less than or equal to 0, return 0 days, hours, minutes and seconds

    const days = Math.floor(totalSeconds / (24 * 60 * 60)); // Calculate the days
    const hours = Math.floor((totalSeconds % (24 * 60 * 60)) / (60 * 60)); // Calculate the hours
    const minutes = Math.floor((totalSeconds % (60 * 60)) / 60); // Calculate the minutes
    const seconds = Math.floor(totalSeconds % 60); // Calculate the seconds

    return { days, hours, minutes, seconds };
};

const countdowns = ref({}); // To store the countdown
const intervals = ref({}); // To store the intervals

const calculateRemainingTime = (endTime) => {
    const now = new Date(); // Get the current date
    const end = new Date(endTime); // Get the end date
    const difference = Math.floor((end - now) / 1000); // Calculate the difference in seconds
    return difference > 0 ? difference : 0; // Return the difference if it is greater than 0, otherwise return 0
};

// Start the counters
onMounted(() => {
    if (props.products) {
        props.products.forEach(product => {
            if (product.auction) {
                // Calculate the remaining time
                countdowns.value[product.auction.id] = calculateRemainingTime(
                    product.auction.end_time
                );

                // Create an interval for each auction
                intervals.value[product.auction.id] = setInterval(() => {
                    if (countdowns.value[product.auction.id] > 0) {
                        countdowns.value[product.auction.id]--;
                        const time = formatTime(countdowns.value[product.auction.id]);
                    } else {
                        clearInterval(intervals.value[product.auction.id]);
                        // Update the auction status if necessary
                        if (product.auction.status) {
                            axios.post(route('auctions.finish', product.auction.id))
                                .then(response => {
                                    // Update the winner information if necessary
                                    if (response.data.winner) {
                                        product.auction.last_bidder = response.data.winner;
                                    }
                                });
                        }
                    }
                }, 1000);
            }
        });
    }
});

// Clean the intervals when the component is unmounted
onUnmounted(() => {
    Object.values(intervals.value).forEach(interval => {
        clearInterval(interval);
    });
});

const form = useForm({
    bid_amount: null,
    auction_id: null
});

const placeBid = (product) => {
    if (!product.bidPrice || product.bidPrice <= product.auction.current_price) {
        alert('La licitació té que ser major al preu actual');
        return;
    }

    form.bid_amount = product.bidPrice;
    form.auction_id = product.auction.id;

    form.post(route('auctions.bid'), {
        preserveScroll: true,
        onSuccess: () => {
            // Update the current price in the interface
            product.auction.current_price = product.bidPrice;
            product.bidPrice = null; // Clean the bid field
        },
        onError: (errors) => {
            alert('Error al realitzar la licitació: ' + Object.values(errors).join('\n'));
        }
    });
};

const downloadAuctionCertificate = (product) => {
    // Create a new PDF document
    const doc = new jsPDF();

    // Configure the document style
    doc.setFont("helvetica");

    // Title
    doc.setFontSize(22);
    doc.setTextColor(44, 62, 80);
    doc.text("Certificat de subhasta", 105, 30, { align: "center" });

    // Decorative line
    doc.setDrawColor(52, 152, 219);
    doc.setLineWidth(0.5);
    doc.line(20, 40, 190, 40);

    // Product information
    doc.setFontSize(14);
    doc.setTextColor(44, 62, 80);
    doc.text("Detalls de la subhasta", 20, 60);

    doc.setFontSize(12);
    doc.setTextColor(52, 73, 94);
    doc.text(`Producte: ${product.name}`, 20, 75);
    doc.text(`Preu final: ${product.auction.current_price}€`, 20, 85);
    doc.text(`Data de finalització: ${new Date(product.auction.end_time).toLocaleDateString()}`, 20, 95);

    // Winner information
    doc.setFontSize(14);
    doc.setTextColor(44, 62, 80);
    doc.text("Informació del Guanyador", 20, 115);

    doc.setFontSize(12);
    doc.setTextColor(52, 73, 94);
    doc.text(`Nombre: ${product.auction.last_bidder.name}`, 20, 130);

    // Footer
    doc.setFontSize(10);
    doc.setTextColor(127, 140, 141);
    doc.text("Aquest document certifica que has guanyat la subhasta del producte mencionat.", 20, 170);
    doc.text(`Data d'emissió: ${new Date().toLocaleDateString()}`, 20, 180);

    // Save the PDF
    doc.save(`subhasta-${product.name}-certificat.pdf`);
};
</script>

<template>

    <Head title="Subhastes"></Head>
    <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-12 text-black">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Section with Wave Effect -->
                <div class="text-center mb-12 relative overflow-hidden p-8 rounded-xl bg-SubastaButton1 shadow-xl">
                    <div class="relative z-10">
                        <h1 class="text-4xl font-bold text-white mb-4">Subhastes actives</h1>
                        <p class="text-lg text-white/90">Descobreix productes únics i participa a emocionants subhastes
                        </p>
                        <Link v-if="isAuthenticated" :href="route('auctions.create')"
                            class="mt-6 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-white bg-SubastaButton1 hover:bg-opacity-90 transition-all transform hover:scale-105 shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Crear nova subhasta
                        </Link>
                    </div>
                    <!-- Animated Wave Background -->
                    <div class="absolute inset-0 z-0 opacity-20">
                        <div class="wave"></div>
                    </div>
                </div>

                <!-- Grid of auctions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="product in products" :key="product.id"
                        class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl transform hover:-translate-y-2">
                        <!-- Image and Badge -->
                        <div class="relative">
                            <img :src="`/storage/${product.image}`" :alt="product.name"
                                class="w-full h-64 object-cover">
                            <div class="absolute top-4 right-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-SubastaButton1 text-white shadow-lg">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Subhasta activa
                                </span>
                            </div>
                        </div>

                        <!-- Auction information -->
                        <div class="p-6 space-y-4">
                            <h3 class="text-xl font-bold text-gray-900">{{ product.name }}</h3>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <p class="text-sm text-blue-600 font-medium">Preu actual</p>
                                    <p class="text-lg font-bold text-blue-900">{{ product.auction?.current_price }}€</p>
                                </div>
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <p class="text-sm text-green-600 font-medium">Preu inicial</p>
                                    <p class="text-lg font-bold text-green-900">{{ product.auction?.start_price }}€</p>
                                </div>
                            </div>

                            <!-- Countdown Timer -->
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-600 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Temps restant
                                </p>
                                <p class="text-lg font-bold text-gray-900">
                                    <template
                                        v-if="product.auction?.id && countdowns[product.auction.id] !== undefined">
                                        <template v-if="countdowns[product.auction.id] > 0">
                                            {{ formatTime(countdowns[product.auction.id]).days }}d
                                            {{ formatTime(countdowns[product.auction.id]).hours }}h
                                            {{ formatTime(countdowns[product.auction.id]).minutes }}m
                                            {{ formatTime(countdowns[product.auction.id]).seconds }}s
                                        </template>
                                        <template v-else>
                                            <div class="text-center">
                                                <p class="text-red-500 font-bold mb-2">Subhasta finalitzada</p>
                                                <p v-if="product.auction.last_bidder" class="text-green-600">
                                                    Guanyador: {{ product.auction.last_bidder.name }}
                                                    <button
                                                        v-if="product.auction.last_bidder.id === $page.props.auth.user?.id"
                                                        @click="downloadAuctionCertificate(product)"
                                                        class="mt-2 inline-flex items-center px-4 py-2 bg-SubastaButton1 text-white rounded-lg hover:bg-opacity-90 transition-colors">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                        Descarregar certificat
                                                    </button>
                                                </p>
                                                <p v-else class="text-gray-500">
                                                    No hi ha hagut licitacions en aquesta subhasta
                                                </p>
                                            </div>
                                        </template>
                                    </template>
                                    <template v-else>
                                        Calculant...
                                    </template>
                                </p>
                            </div>

                            <!-- Field to enter the bid price -->
                            <div v-if="countdowns[product.auction.id] > 0">
                                <div class="mt-4">
                                    <input aria-label="Product Price" type="number" v-model="product.bidPrice"
                                        :min="product.auction?.current_price + 1"
                                        :placeholder="`Mínim ${(product.auction?.current_price + 1)}€`"
                                        class="border rounded-lg p-2 w-full"
                                        :class="{ 'border-red-500': product.bidPrice <= product.auction?.current_price }" />
                                    <p v-if="product.bidPrice <= product.auction?.current_price"
                                        class="text-red-500 text-sm mt-1">
                                        La licitació ha de ser major al preu actual
                                    </p>
                                </div>

                                <!-- Action buttons -->
                                <div class="grid grid-cols-2 gap-4 pt-4">
                                    <Link :href="route('product.show', product.id)"
                                        class="inline-flex justify-center items-center px-4 py-2 border border-SubastaButton1 text-sm font-medium rounded-lg text-SubastaButton1 bg-white hover:bg-gray-50 transition-all">
                                    Veure detalls
                                    </Link>
                                    <button v-if="isAuthenticated" @click="placeBid(product)"
                                        :disabled="!product.bidPrice || product.bidPrice <= product.auction?.current_price"
                                        class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-500 hover:bg-green-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                        Licitar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="products.length === 0" class="text-center py-12 bg-white rounded-2xl shadow-lg">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hi ha subhastes actives</h3>
                    <p class="mt-1 text-sm text-gray-500">Torna més tard per veure noves subhastes</p>
                </div>
            </div>
        </div>
    </component>
    <Cookies />
</template>

<style scoped>
/* Custom colors */
.bg-custom-blue {
    background-color: #004266;
}

.bg-custom-blue-dark {
    background-color: #003252;
}

.bg-custom-blue-light {
    background-color: #006699;
}

.text-custom-blue {
    color: #004266;
}

/* Existing effects */
@keyframes wave {
    0% {
        transform: translateX(-50%) rotateZ(0deg);
    }

    50% {
        transform: translateX(-25%) rotateZ(180deg);
    }

    100% {
        transform: translateX(-50%) rotateZ(360deg);
    }
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

.countdown-expired {
    color: #EF4444;
}

/* Backgrounds and animations */
.wave-bg {
    background-color: #004266;
}

/* Add this new style */
.text-black {
    color: #000000;
}
</style>