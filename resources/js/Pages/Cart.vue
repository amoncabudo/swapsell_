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

console.log('Products in basket:', props.products_baskets); //Log products in basket

const total = computed(() => { //Compute total
    if (!props.products_baskets) return 0; //If no products in basket, return 0
    return props.products_baskets.reduce((sum, product) => { //Reduce products in basket
        const price = parseFloat(product?.price) || 0; //Parse float price
        return sum + price; //Return sum of price
    }, 0); //Return 0
});

function formatPrice(price) { //Format price
    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(price);
}

function removeFromBasket(product) { //Remove from basket
    axios.post(route('baskets_products'), { id: product.id }) //Post request to remove from basket
        .then(response => { //Then response
            // Delete the product from the list if we are on the cart page
            const index = props.products_baskets.findIndex(p => p.id === product.id); //Find index of product
            if (index !== -1) { //If index is not -1
                props.products_baskets.splice(index, 1); //Splice product from basket
            }
        })
        .catch(error => { //Catch error
            console.error("Error al actualizar el carrito:", error); //Log error
        });
}

const paypalLoaded = ref(false); //Paypal loaded
const paypalClientId = import.meta.env.VITE_PAYPAL_CLIENT_ID; //Paypal client id

onMounted(() => { 
  const script = document.createElement('script'); //Create script
  script.src = `https://www.paypal.com/sdk/js?client-id=${paypalClientId}&currency=EUR`; //Script src
  script.async = true; //Script async

  script.onload = () => { //On load
    paypalLoaded.value = true; //Paypal loaded
    window.paypal.Buttons({
      fundingSource: paypal.FUNDING.PAYPAL, //Funding source
      style: {
        color: 'black', //Color
        shape: 'rect', //Shape
        layout: 'vertical', //Layout
        label: 'paypal' //Label
      },
      createOrder: (data, actions) => { //Create order
        return actions.order.create({ //Return actions order create
          purchase_units: [{ //Purchase units
            amount: { //Amount
              currency_code: 'EUR', //Currency code
              value: (total.value * 1.21).toFixed(2) // Total amb IVA
            }
          }]
        });
      },
      onApprove: (data, actions) => { //On approve
        return actions.order.capture().then((details) => { //Return actions order capture
            // Process each product in the cart
            const processTransactions = props.products_baskets.map(product => { //Process transactions
                return axios.post(route('transactions.store'), { //Return axios post request to store transactions
                    product_id: product.id, //Product id
                    category_id: product.category_id, //Category id
                    name: product.name, //Name
                    description: product.description, //Description
                    price: product.price, //Price
                    longitude: product.longitude || '0', //Longitude
                    latitude: product.latitude || '0', //Latitude
                    image: product.image, //Image
                    status: 'completed' //Status
                });
            });

            // Wait for all transactions to complete
            Promise.all(processTransactions)
                .then(() => {
                    // Remove products from the cart
                    props.products_baskets.forEach(product => {
                        removeFromBasket(product);
                    });
                })
                .then(() => {
                    // Show success message
                    Swal.fire({
                        title: '¡Èxit!', //Success title
                        text: 'Pagament processat correctament!', //Success text
                        icon: 'success', //Success icon
                        confirmButtonText: 'Acceptar' //Confirm button text
                    }).then(() => {
                        window.location.href = route('reviews.index'); //Redirect to reviews index
                    });
                })
                .catch((error) => {
                    console.error('Error:', error); //Log error
                    Swal.fire({
                        title: 'Error', //Error title
                        text: 'Hi ha hagut un problema en processar el pagament. Si us plau, contacta amb suport.', //Error text
                        icon: 'error', //Error icon
                        confirmButtonText: 'Acceptar' //Confirm button text
                    });
                });
        });
      }
    }).render('#paypal-button-container'); //Render paypal button container //Render paypal button container
  };

  document.body.appendChild(script); //Append script to body //Append script to body
});
</script>

<template>
    <Head title = "Carret"></Head>
    <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
        <div class="flex flex-col lg:flex-row min-h-screen bg-gray-100 p-4">
            <!-- Summary of the purchase -->
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

            <!-- Summary of the purchase -->
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