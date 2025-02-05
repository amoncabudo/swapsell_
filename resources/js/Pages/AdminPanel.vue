<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';
const flashMessage = ref(null);
const isModalEventOpen = ref(false);
const isModalUserOpen = ref(false);
const isModalProductOpen = ref(false);

defineProps({
    isAuthenticated: Boolean,
});

// Modal User

const openModalU = () => {
    isModalUserOpen.value = true;
};
const form = useForm({
    name: '',
    surname: '',
    email: '',
    password: '',
});
const closeModalU = () => {
    isModalUserOpen.value = false;
};

function addUser() {
    console.log("dkasjg");
    axios.post(route('users'), form).then(data => {
        console.log(data.data);
        closeModalU();
        flashMessage.value = 'Prova';
        console.log("---prueba----");
    })
};

//Modal Events

const openModalE = () => {
    isModalEventOpen.value = true;
};
const event = useForm({
    title: '',
    description: '',
    date: '',
    time: '',
    longitude: '',
    latitude: '',
    image: ''

    
});
const closeModalE = () => {
    isModalEventOpen.value = false;
};

function addEvent(){
        console.log("dkasjg");
    axios.post(route('events'), event).then(data => {
        console.log(data.data);
        closeModalE();
        flashMessage.value = 'Prova';
        console.log("---prueba----");
    })
};

// ModalProducts

const openModalP = () => {
    isModalProductOpen.value = true;
};
const product = useForm({
    name: '',
    description: '',
    price: '',
    longitude: '',
    latitude: '',
    image: '',
    status: ''

    
});
const closeModalP = () => {
    isModalProductOpen.value = false;
};

function addProduct(){
        console.log("dkasjg");
    axios.post(route('sell'), product).then(data => {
        console.log(data.data);
        closeModalP();
        flashMessage.value = 'Prova';
        console.log("---prueba----");
    })
};

</script>

<template>
    <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="min-h-screen bg-gray-100">
        <div class="container mx-auto p-6">
            <h1 class="text-4xl font-bold text-center mb-8 text-gray-800 mt-12">PANELL D'ADMINISTRADOR</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <svg class="mx-auto mb-2 w-12 h-12 text-gray-800 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z"
                            clip-rule="evenodd" />
                    </svg>
                    <!-- <img src="" alt="AddUsers" class="mx-auto mb-2 w-12 h-12" /> -->
                    <h2 class="text-xl font-semibold mb-2 text-gray-800">AFEGIR USUARIS</h2>
                    <p class="text-gray-800">Apartat per afegir usuaris</p>
                    <div class="mt-4">
                        <button aria-label="Create User" @click="openModalU"
                            class="bg-SubastaButton1 text-white px-4 py-2 rounded hover:bg-blue-600">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 12h14m-7 7V5" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <svg class="mx-auto mb-2 w-12 h-12 text-gray-800 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M8 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1h2a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2Zm6 1h-4v2H9a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2h-1V4Zm-3 8a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Zm2 5a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Z"
                            clip-rule="evenodd" />
                    </svg>
                    <h2 class="text-xl font-semibold mb-2 text-gray-800">AFEGIR ESDEVENIMENTS</h2>
                    <p class="text-gray-800">Apartat per afegir esdeveniments</p>
                    <div class="mt-4">
                        <button aria-label="Create Event" @click="openModalE" 
                        class="bg-SubastaButton1 text-white px-4 py-2 rounded hover:bg-blue-600">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 12h14m-7 7V5" />
                            </svg></button>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <svg class="mx-auto mb-2 w-12 h-12 text-gray-800 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M14 7h-4v3a1 1 0 0 1-2 0V7H6a1 1 0 0 0-.997.923l-.917 11.924A2 2 0 0 0 6.08 22h11.84a2 2 0 0 0 1.994-2.153l-.917-11.924A1 1 0 0 0 18 7h-2v3a1 1 0 1 1-2 0V7Zm-2-3a2 2 0 0 0-2 2v1H8V6a4 4 0 0 1 8 0v1h-2V6a2 2 0 0 0-2-2Z"
                            clip-rule="evenodd" />
                    </svg>
                    <!-- <img src="" alt="AddProducts" class="mx-auto mb-2 w-12 h-12" /> -->
                    <h2 class="text-xl font-semibold mb-2 text-gray-800">AFEGIR PRODUCTES</h2>
                    <p class="text-gray-800">Apartat per afegir productes</p>
                    <div class="mt-4">
                        <button aria-label="Create Product" @click="openModalP" 
                        class="bg-SubastaButton1 text-white px-4 py-2 rounded hover:bg-blue-600">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 12h14m-7 7V5" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <svg class="mx-auto mb-2 w-12 h-12 text-gray-800 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                            d="M9 8h10M9 12h10M9 16h10M4.99 8H5m-.02 4h.01m0 4H5" />
                    </svg>
                    <!-- <img src="" alt="ListUsers" class="mx-auto mb-2 w-12 h-12" /> -->
                    <h2 class="text-xl font-semibold mb-2 text-gray-800">LLISTAT D'USUARIS</h2>
                    <p class="text-gray-800">Apartat per visualitzar i gestionar els usuaris</p>
                    <div class="mt-4">
                        <button aria-label="User List" class="bg-SubastaButton1 text-white px-4 py-2 rounded hover:bg-blue-600">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 12H5m14 0-4 4m4-4-4-4" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <svg class="mx-auto mb-2 w-12 h-12 text-gray-800 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                            d="M9 8h10M9 12h10M9 16h10M4.99 8H5m-.02 4h.01m0 4H5" />
                    </svg>
                    <!-- <img src="" alt="ListEvents" class="mx-auto mb-2 w-12 h-12" /> -->
                    <h2 class="text-xl font-semibold mb-2 text-gray-800">LLISTAT D'ESDEVENIMENTS</h2>
                    <p class="text-gray-800">Apartat per visualitzar i gestionar els esdeveniments</p>
                    <div class="mt-4">
                        <button aria-label="Events List" class="bg-SubastaButton1 text-white px-4 py-2 rounded hover:bg-blue-600">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 12H5m14 0-4 4m4-4-4-4" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <svg class="mx-auto mb-2 w-12 h-12 text-gray-800 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                            d="M9 8h10M9 12h10M9 16h10M4.99 8H5m-.02 4h.01m0 4H5" />
                    </svg>
                    <!-- <img src="" alt="ListProducts" class="mx-auto mb-2 w-12 h-12" /> -->
                    <h2 class="text-xl font-semibold mb-2 text-gray-800">LLISTAT DE PRODUCTES</h2>
                    <p class="text-gray-800">Apartat per visualitzar i gestionar els productes</p>
                    <div class="mt-4">
                        <button aria-label="Products List" class="bg-SubastaButton1 text-white px-4 py-2 rounded hover:bg-blue-600">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 12H5m14 0-4 4m4-4-4-4" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal add user -->
    <div v-if="isModalUserOpen" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg w-1/2">
            <h2 class="text-xl font-semibold mb-2">Crear Usuario</h2>
            <form @submit.prevent="addUser">
                <div>
                    <label for="name" class="block text-black">Nom de l'Usuari:</label>
                    <input type="text" id="name" v-model="form.name" class="border rounded w-full p-2 text-black"
                        required />
                </div>
                <div>
                    <label for="surname" class="block text-black">Cognoms de l'Usuari:</label>
                    <input type="text" id="surname" v-model="form.surname" class="border rounded w-full p-2 text-black"
                        required />
                </div>

                <div class="mt-4">
                    <label for="email" class="block text-black">Correu Electrònic:</label>
                    <input type="email" id="email" v-model="form.email" class="border rounded w-full p-2 text-black"
                        required />
                </div>
                <div>
                    <label for="password" class="block text-black">Contrasenya:</label>
                    <input type="password" id="password" v-model="form.password"
                        class="border rounded w-full p-2 text-black" required />
                </div>
                <div class="mt-4">
                    <button type="submit" class="bg-SubastaButton1 text-white px-4 py-2 rounded">Crear</button>
                    <button type="button" @click="closeModalU"
                        class="bg-red-500 text-white px-4 py-2 rounded ml-4">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal add event-->
    <div v-if="isModalEventOpen" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg w-1/2">
            <h2 class="text-xl font-semibold mb-2">Crear Usuario</h2>
            <form @submit.prevent="addEvent">
                <div>
                    <label for="title" class="block text-black">Titol de l'event:</label>
                    <input type="text" id="title" v-model="event.title" class="border rounded w-full p-2 text-black"
                        required />
                </div>
                <div>
                    <label for="description" class="block text-black">Descripció:</label>
                    <input type="text" id="description" v-model="event.description" class="border rounded w-full p-2 text-black"
                        required />
                </div>

                <div class="mt-4">
                    <label for="date" class="block text-black">Dia del evento:</label>
                    <input type="date" id="date" v-model="event.date" class="border rounded w-full p-2 text-black"
                        required />
                </div>
                <div class="mt-4">
                    <label for="time" class="block text-black">Hora del evento:</label>
                    <input type="time" id="time" v-model="event.time" class="border rounded w-full p-2 text-black"
                        required />
                </div>
                <div class="mt-4">
                    <label for="longitude" class="block text-black">Longitud:</label>
                    <input type="text" id="longitude" v-model="event.longitude" class="border rounded w-full p-2 text-black"
                        required />
                </div>
                <div class="mt-4">
                    <label for="latitude" class="block text-black">Latitud:</label>
                    <input type="text" id="latitude" v-model="event.latitude" class="border rounded w-full p-2 text-black"
                        required />
                </div>
                <div class="mt-4">
                    <label for="imge" class="block text-black">Imatge:</label>
                    <input type="text" id="imge" v-model="event.image" class="border rounded w-full p-2 text-black"
                        required />
                </div>
                <div class="mt-4">
                    <button type="submit" class="bg-SubastaButton1 text-white px-4 py-2 rounded">Crear</button>
                    <button type="button" @click="closeModalE"
                        class="bg-red-500 text-white px-4 py-2 rounded ml-4">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    <div v-if="isModalProductOpen" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg w-1/2">
            <h2 class="text-xl font-semibold mb-2">Crear Usuario</h2>
            <form @submit.prevent="addProduct">
                <div>
                    <label for="name" class="block text-black">Nom del producte:</label>
                    <input type="text" id="name" v-model="product.name" class="border rounded w-full p-2 text-black"
                        required />
                </div>
                <div>
                    <label for="description" class="block text-black">Descripció:</label>
                    <textarea id="description" v-model="product.description" class="border rounded w-full p-2 text-black"
                        required>
                    </textarea>
                </div>

                <div class="mt-4">
                    <label for="price" class="block text-black">Preu del producte:</label>
                    <input type="number" id="price" v-model="product.price" class="border rounded w-full p-2 text-black"
                        required />
                </div>
                <div class="mt-4">
                    <label for="longitude" class="block text-black">Longitud:</label>
                    <input type="text" id="longitude" v-model="product.longitude" class="border rounded w-full p-2 text-black"
                        required />
                </div>
                <div class="mt-4">
                    <label for="latitude" class="block text-black">Latitud:</label>
                    <input type="text" id="latitude" v-model="product.latitude" class="border rounded w-full p-2 text-black"
                        required />
                </div>
                <div class="mt-4">
                    <label for="image" class="block text-black">Imatges:</label>
                    <input type="text" id="image" v-model="product.image" class="border rounded w-full p-2 text-black"
                        required />
                </div>
                <div class="mt-4">
                    <label for="status" class="block text-black">Estat del producte:</label>
                    <input type="text" id="status" v-model="product.status" class="border rounded w-full p-2 text-black"
                        required />
                </div>
                <div class="mt-4">
                    <button type="submit" class="bg-SubastaButton1 text-white px-4 py-2 rounded">Crear</button>
                    <button type="button" @click="closeModalP"
                        class="bg-red-500 text-white px-4 py-2 rounded ml-4">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    </component>
</template>