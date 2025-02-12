<script setup>
import { ref, computed, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2';
import { Head } from '@inertiajs/vue3';
import Cookies from "@/Components/Cookies.vue";

const props = defineProps({
    products_baskets: Array,
    isAuthenticated: Boolean
});

console.log('Products in basket:', props.products_baskets);

const total = computed(() => {
    if (!props.products_baskets) return 0;
    return props.products_baskets.reduce((sum, product) => {
        const price = parseFloat(product?.price) || 0;
        return sum + price;
    }, 0);
});

function formatPrice(price) {
    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(price);
}

function removeFromBasket(product) {
    axios.post(route('baskets_products'), { id: product.id })
        .then(response => {
            // Eliminar el producto de la lista si estamos en la página del carrito
            const index = props.products_baskets.findIndex(p => p.id === product.id);
            if (index !== -1) {
                props.products_baskets.splice(index, 1);
            }
        })
        .catch(error => {
            console.error("Error al actualizar el carrito:", error);
        });
}

const paypalLoaded = ref(false);
const paypalClientId = import.meta.env.VITE_PAYPAL_CLIENT_ID;

onMounted(() => {
  const script = document.createElement('script');
  script.src = `https://www.paypal.com/sdk/js?client-id=${paypalClientId}&currency=EUR`;
  script.async = true;
  
  script.onload = () => {
    paypalLoaded.value = true;
    window.paypal.Buttons({
      fundingSource: paypal.FUNDING.PAYPAL,
      style: {
        color: 'black',
        shape: 'rect',
        layout: 'vertical',
        label: 'paypal'
      },
      createOrder: (data, actions) => {
        return actions.order.create({
          purchase_units: [{
            amount: {
              currency_code: 'EUR',
              value: (total.value * 1.21).toFixed(2) // Total con IVA
            }
          }]
        });
      },
      onApprove: (data, actions) => {
        return actions.order.capture().then((details) => {
            // Procesamos cada producto en el carrito
            const processTransactions = props.products_baskets.map(product => {
                return axios.post(route('transactions.store'), {
                    product_id: product.id,
                    category_id: product.category_id,
                    name: product.name,
                    description: product.description,
                    price: product.price,
                    longitude: product.longitude || '0',
                    latitude: product.latitude || '0',
                    image: product.image,
                    status: 'completed'
                });
            });

            // Esperamos a que todas las transacciones se completen
            Promise.all(processTransactions)
                .then(() => {
                    // Eliminamos los productos del carrito
                    props.products_baskets.forEach(product => {
                        removeFromBasket(product);
                    });
                })
                .then(() => {
                    // Mostramos mensaje de éxitoop
                    Swal.fire({
                        title: '¡Èxit!',
                        text: 'Pagament processat correctament!',
                        icon: 'success',
                        confirmButtonText: 'Acceptar'
                    }).then(() => {
                        window.location.href = route('reviews.index');
                    });
                })
                .catch((error) => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Hi ha hagut un problema en processar el pagament. Si us plau, contacta amb suport.',
                        icon: 'error',
                        confirmButtonText: 'Acceptar'
                    });
                });
        });
      }
    }).render('#paypal-button-container');
  };

  document.body.appendChild(script);
});
</script>

<template>
    <Head title = "Carret"></Head>
    <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
        <div class="flex flex-col lg:flex-row min-h-screen bg-gray-100 p-4">
            <!-- Lista de productos -->
            <div class="w-full lg:w-2/3 pr-4">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">El meu carret</h2>
                    <div v-if="!products_baskets || products_baskets.length === 0" class="text-center py-8">
                        <p class="text-gray-500">No hi ha productes al carret</p>
                    </div>
                    <div v-else class="space-y-6">
                        <div v-for="product in products_baskets" :key="product.id" 
                            class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <img :src="`/storage/${product.image}`" :alt="product.name"
                                class="w-24 h-24 object-cover rounded-lg">
                            <div class="ml-6 flex-grow">
                                <Link :href="route('product.show', product.id)" class="text-lg font-semibold text-gray-800 hover:text-blue-600">
                                    {{ product.name }}
                                </Link>
                                <p class="text-gray-600 mt-1">{{ product.description }}</p>
                                <p class="text-blue-600 font-bold mt-2">{{ formatPrice(product.price) }}</p>
                            </div>
                            <button @click="removeFromBasket(product)" class="ml-4 text-red-500 hover:text-red-700" aria-label="Delete product from basket">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen de la compra -->
            <div class="w-full lg:w-1/3 mt-6 lg:mt-0">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800">Resum de la compra</h2>
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-black font-semibold">{{ formatPrice(total) }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">IVA (21%)</span>
                            <span class="text-black font-semibold">{{ formatPrice(total * 0.21) }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-4 mt-2">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-800">Total</span>
                                <span class="text-lg font-bold text-blue-600">{{ formatPrice(total * 1.21) }}</span>
                            </div>
                        </div>
                        <div class="w-full mt-6">
                            <div id="paypal-button-container" class="w-full"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </component>

    <Cookies />

</template>

<style scoped>
.bg-gray-100 {
    min-height: calc(100vh - 4rem);
}

#paypal-button-container {
  min-height: 45px;
  width: 100%;
}

.paypal-buttons {
    display: block !important;
}

.paypal-button-number-2 {
    display: none !important;
}
</style>