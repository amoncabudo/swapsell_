<template>
    <div class="bg-white">
        <main class="p-8">
            <h1 class="text-4xl font-bold text-center mb-8">LLISTA DE SUBHASTES</h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div v-for="auction in auctions" :key="auction.id"
                    class="bg-white border p-4 text-center shadow-md hover:animate-bounce-once">
                    <h2 class="text-lg font-semibold">TÍTOL PRODUCTE</h2>
                    <div class="flex justify-center items-center h-32 bg-gray-300 mb-4">
                        <img :src="auction.image" alt="Product Image"
                            class="w-20 h-20 object-cover hover:animate-pulse-once">
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>USUARI</span>
                        <span>ILICITACIÓ</span>
                        
                        <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 
                        focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 
                        dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none 
                        dark:focus:ring-blue-800">ACCEDIR-HI</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <Footer />
</template>

<script>
import Footer from "../Components/Footer.vue";
export default {
    data() {
        return {
            auctions: [
                { id: 1, image: '/img/1.jpeg' },
                { id: 2, image: '/img/2.jpeg' },
                { id: 3, image: 'https://via.placeholder.com/150' },
                { id: 4, image: 'https://via.placeholder.com/150' },
                { id: 5, image: 'https://via.placeholder.com/150' },
                { id: 6, image: 'https://via.placeholder.com/150' },
                { id: 7, image: 'https://via.placeholder.com/150' },
                { id: 8, image: 'https://via.placeholder.com/150' }
            ]
        };
    },
    components: {
        Footer,
    }
};
</script>

<style scoped>
/* Animación Bounce */
@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

/* Animación Pulse */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

/* Bounce Animation */
.hover\:animate-bounce-once:hover {
    animation: bounce 1s forwards; /* Ejecuta una sola vez */
}

/* Pulse Animation */
.hover\:animate-pulse-once:hover {
    animation: pulse 1s forwards; /* Ejecuta una sola vez */
}
</style>
