<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { ref, onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { Events } from 'leaflet';
import AOS from 'aos';
import 'aos/dist/aos.css';

//Define variables
const flashMessage = ref(null);
const isModalEventOpen = ref(false);
const isModalUserOpen = ref(false);
const isModalProductOpen = ref(false);
const isEditUserModalOpen = ref(false);
const showToast = ref(false);
const toastMessage = ref('');
const toastType = ref('success');
const products = ref([]);
const users = ref([]);
const editingUser = ref(null);
const events = ref([]);
//Edit User Form Variables
const editUserForm = useForm({
    id: '',
    name: '',
    surname: '',
    email: '',
    password: '',
    role: '',
    image: null
});
//Define variables for roles
const roles = [
    { id: 0, name: 'Administrador' },
    { id: 1, name: 'Scrum Manager' },
    { id: 2, name: 'Usuari' }
];
//Define variables for products
const product = ref({
    name: '',
    description: '',
    price: '',
    longitude: '',
    latitude: '',
    status: 'active',
    category_id: '1',
    image: null
});
//Define variables for authentication
defineProps({
    isAuthenticated: Boolean,
});

// Modal User
const openModalU = () => { //Open modal for add user
    isModalUserOpen.value = true;
};
const form = useForm({ //Form for add user
    name: '',
    surname: '',
    email: '',
    password: '',
});
const closeModalU = () => { //Close modal for add user
    isModalUserOpen.value = false;
};

//Function for add user
function addUser() { //Add user
    axios.post(route('users'), form) //Post request for add user
        .then(response => {
            closeModalU(); //Close modal for add user
            toastMessage.value = 'Usuari creat correctament!'; //Toast message for add user
            toastType.value = 'success'; //Toast type for add user
            showToast.value = true; //Show toast for add user
            setTimeout(() => {
                showToast.value = false; //Hide toast for add user
            }, 3000); //Hide toast for add user
        })
        .catch(error => {
            toastMessage.value = 'Error al crear usuari'; //Toast message for add user
            toastType.value = 'error';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        });
}
//Modal Events
const openModalE = () => { //Open modal for add event
    isModalEventOpen.value = true;
};
const event = useForm({ //Form for add event
    title: '',
    description: '',
    date: '',
    time: '',
    longitude: '',
    latitude: '',
    image: null
});
const closeModalE = () => { //Close modal for add event
    isModalEventOpen.value = false;
};
//Function for add event
function addEvent() { //Add event
    event.post(route('event.addEvent'), {
        onSuccess: () => {
            closeModalE(); //Close modal for add event  
            // Update the events list after creating a new one
            axios.get(route('event.addEvent')).then(response => {
                events.value = response.data;
            });
            toastMessage.value = 'Esdeveniment creat correctament!'; //Toast message for add event
            showToast.value = true; //Show toast for add event
            setTimeout(() => {
                showToast.value = false; //Hide toast for add event
            }, 3000); //Hide toast for add event
        },
        onError: () => {
            toastMessage.value = 'Error al crear esdeveniment'; //Toast message for add event
            toastType.value = 'error'; //Toast type for add event
            showToast.value = true; //Show toast for add event
            setTimeout(() => {
                showToast.value = false; //Hide toast for add event
            }, 3000); //Hide toast for add event
        }
    });
}
// Modal User Edit
const openEditUserModal = (user) => { //Open modal for edit user 
    editUserForm.id = user.id; //Set id for edit user
    editUserForm.name = user.name; //Set name for edit user
    editUserForm.surname = user.surname; //Set surname for edit user
    editUserForm.email = user.email; //Set email for edit user
    editUserForm.role = user.role || '0'; //Set role for edit user
    editUserForm.password = ''; //Set password for edit user
    editUserForm.image = null;
    isEditUserModalOpen.value = true;
};

const closeEditUserModal = () => { //Close modal for edit user
    isEditUserModalOpen.value = false; //Set isEditUserModalOpen to false
    editUserForm.reset(); //Reset editUserForm
};

function updateUser() { //Update user
    const userData = { //Set userData
        name: editUserForm.name, //Set name for userData
        surname: editUserForm.surname, //Set surname for userData
        email: editUserForm.email, //Set email for userData
        role: editUserForm.role //Set role for userData
    };
    // Only include the password if it has been entered
    if (editUserForm.password) {
        userData.password = editUserForm.password;
    }

    axios.put(route('users.update', editUserForm.id), userData) //Put request for update user
        .then(response => {
            closeEditUserModal(); //Close modal for edit user
            // Update the users list after updating one
            const index = users.value.findIndex(u => u.id === editUserForm.id);
            if (index !== -1) {
                users.value[index] = response.data;
            }
            toastMessage.value = 'Usuari actualitzat correctament!'; //Toast message for update user
            toastType.value = 'success'; //Toast type for update user
            showToast.value = true; //Show toast for update user
            setTimeout(() => {
                showToast.value = false; //Hide toast for update user
            }, 3000); //Hide toast for update user
        })
        .catch(error => {
            console.error('Error:', error);
            toastMessage.value = 'Error al actualitzar l\'usuari'; //Toast message for update user
            toastType.value = 'error'; //Toast type for update user
            showToast.value = true; //Show toast for update user
            setTimeout(() => {
                showToast.value = false; //Hide toast for update user
            }, 3000); //Hide toast for update user
        });
}

//Function for delete user
const deleteUser = async (userId) => { //Delete user
    if (confirm('¿Estás segur de que vols esborrar aquest usuari?')) { //Confirmation for delete user
        try {
            await axios.delete(route('users.destroy', userId)); //Delete request for delete user
            // Update the users list after deleting one
            users.value = users.value.filter(user => user.id !== userId);
            toastMessage.value = 'Usuari eliminat correctament'; //Toast message for delete user
            toastType.value = 'success'; //Toast type for delete user
            showToast.value = true; //Show toast for delete user
            setTimeout(() => {
                showToast.value = false; //Hide toast for delete user
            }, 3000); //Hide toast for delete user
        } catch (error) {
            console.error('Error al eliminar el usuari:', error); //Error for delete user
            toastMessage.value = 'Error al eliminar l\'usuari'; //Toast message for delete user
            toastType.value = 'error'; //Toast type for delete user
            showToast.value = true; //Show toast for delete user
            setTimeout(() => {
                showToast.value = false; //Hide toast for delete user
            }, 3000); //Hide toast for delete user
        }
    }
};
//Mounted
onMounted(async () => {
    try {
        const responseUsers = await axios.get(route('users.list')); //Get request for list users
        users.value = responseUsers.data; //Set users

    } catch (error) {
        console.error('Error al cargar les dades:', error.response ? error.response.data : error); //Error for list users
        toastMessage.value = 'Error al cargar les dades'; //Toast message for list users
        toastType.value = 'error'; //Toast type for list users
        showToast.value = true; //Show toast for list users
        setTimeout(() => {
            showToast.value = false; //Hide toast for list users
        }, 3000); //Hide toast for list users
    }
});

//Function for format currency
const formatCurrency = (value) => { //Format currency
    return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'EUR'
    }).format(value);
};

//Function for handle file upload
function handleFileUpload(e) { //Handle file upload
    const file = e.target.files[0]; //Get file
    if (file) { //If file exists
        event.image = file; //Set file
    }
}

function handleProductImageUpload(event) { //Handle product image upload
    const file = event.target.files[0]; //Get file
    if (file) { //If file exists
        product.value.image = file; //Set file
    }
}

// Modal add product
const openModalP = () => { //Open modal for add product
    isModalProductOpen.value = true; //Set isModalProductOpen to true
};

const closeModalP = () => { //Close modal for add product
    isModalProductOpen.value = false; //Set isModalProductOpen to false
    // Clean the form
    product.value = {
        name: '',
        description: '',
        price: '',
        longitude: '',
        latitude: '',
        status: 'active',
        category_id: '',
        image: null
    };
};

function addProduct() { //Add product
    const formData = new FormData(); //Create form data
    for (const key in product.value) {
        formData.append(key, product.value[key]);
    }
    console.log(formData); //Log form data
    axios.post(route('products.addProduct'), formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            closeModalP(); //Close modal for add product
            product.value.push(response.data); //Add product to the list
            toastMessage.value = 'Producte creat correctament!'; //Toast message for add product
            toastType.value = 'success'; //Toast type for add product
            showToast.value = true; //Show toast for add product
            setTimeout(() => {
                showToast.value = false; //Hide toast for add product
            }, 3000); //Hide toast for add product
        })
        .catch(error => {
            console.error('Error al crear el producte:', error.response.data); //Error for add product
            toastMessage.value = 'Error al crear el producte'; //Toast message for add product
            toastType.value = 'error'; //Toast type for add product
            showToast.value = true; //Show toast for add product
            setTimeout(() => {
                showToast.value = false; //Hide toast for add product
            }, 3000); //Hide toast for add product
        });
}
</script>

<template>
    <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
        <div class="min-h-screen bg-white">
            <div class="container mx-auto p-4 md:p-8">
                <!-- Header Section -->
                <div class="text-center mb-16" data-aos="fade-down">
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-800 mb-4 tracking-tight">
                        PANELL D'ADMINISTRADOR
                    </h1>
                    <p class="text-gray-600 text-xl font-light">Gestiona usuaris, esdeveniments i productes</p>
                </div>

                <!-- Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                    <!-- User Card -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 transform hover:scale-105 transition-all duration-300 border border-gray-100" data-aos="fade-right">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold mb-4 text-gray-800 text-center">Usuaris</h2>
                        <p class="text-gray-600 text-center mb-8">Gestiona els usuaris del sistema</p>
                        <button @click="openModalU" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-4 rounded-xl hover:shadow-lg flex items-center justify-center gap-3 group transition-all duration-300 hover:-translate-y-1">
                            <span class="text-lg">Afegir Usuari</span>
                            <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Event Card -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 transform hover:scale-105 transition-all duration-300 border border-gray-100" data-aos="fade-up">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1h2a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2Z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold mb-4 text-gray-800 text-center">Esdeveniments</h2>
                        <p class="text-gray-600 text-center mb-8">Gestiona els esdeveniments del sistema</p>
                        <button @click="openModalE" class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-4 rounded-xl hover:shadow-lg flex items-center justify-center gap-3 group transition-all duration-300 hover:-translate-y-1">
                            <span class="text-lg">Afegir Esdeveniment</span>
                            <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Product Card -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 transform hover:scale-105 transition-all duration-300 border border-gray-100" data-aos="fade-left">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M14 7h-4v3a1 1 0 0 1-2 0V7H6a1 1 0 0 0-.997.923l-.917 11.924A2 2 0 0 0 6.08 22h11.84a2 2 0 0 0 1.994-2.153l-.917-11.924A1 1 0 0 0 18 7h-2v3a1 1 0 1 1-2 0V7Z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold mb-4 text-gray-800 text-center">Productes</h2>
                        <p class="text-gray-600 text-center mb-8">Gestiona els productes del sistema</p>
                        <button @click="openModalP" class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white py-4 rounded-xl hover:shadow-lg flex items-center justify-center gap-3 group transition-all duration-300 hover:-translate-y-1">
                            <span class="text-lg">Afegir Producte</span>
                            <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Users List Section -->
                <div class="mt-16" data-aos="fade-up">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        <div class="p-8 border-b border-gray-100">
                            <h2 class="text-3xl font-bold text-gray-800">Llista d'Usuaris</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Nom</th>
                                        <th class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Cognoms</th>
                                        <th class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                                        <th class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Rol</th>
                                        <th class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Accions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 transition-colors">
                                        <td class="px-8 py-5">
                                            <div class="text-sm font-medium text-gray-800">{{ user.name }}</div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="text-sm text-gray-800">{{ user.surname }}</div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="text-sm text-gray-800">{{ user.email }}</div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <span class="px-4 py-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                  :class="{
                                                      'bg-emerald-100 text-emerald-800': user.role === 0,
                                                      'bg-blue-100 text-blue-800': user.role === 1,
                                                      'bg-purple-100 text-purple-800': user.role === 2
                                                  }">
                                                {{ roles.find(role => role.id === user.role)?.name || 'Desconegut' }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 space-x-4">
                                            <button @click="openEditUserModal(user)" 
                                                class="text-blue-600 hover:text-blue-800 transition-colors">
                                                Editar
                                            </button>
                                            <button @click="deleteUser(user.id)" 
                                                class="text-red-600 hover:text-red-800 transition-colors">
                                                Esborrar
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Toast Message -->
                <div v-if="showToast" 
                    class="fixed bottom-8 right-8 px-8 py-4 rounded-xl shadow-xl transition-all duration-500 transform"
                    :class="toastType === 'success' ? 'bg-green-500' : 'bg-red-500'">
                    <p class="text-white font-medium text-lg">{{ toastMessage }}</p>
                </div>

                <!-- Modal add user -->
                <div v-if="isModalUserOpen" class="fixed inset-0 flex items-center justify-center z-50">
                    <!-- Overlay con efecto blur -->
                    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
                    
                    <div class="bg-white p-8 rounded-2xl shadow-2xl w-1/2 relative transform transition-all duration-300 scale-100">
                        <div class="border-b border-gray-100 pb-4 mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Crear Usuari</h2>
                        </div>
                        
                        <form @submit.prevent="addUser" enctype="multipart/form-data" class="space-y-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom de l'Usuari</label>
                                    <input type="text" id="name" v-model="form.name"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800" 
                                        required />
                                </div>

                                <div class="form-group">
                                    <label for="surname" class="block text-sm font-medium text-gray-700 mb-2">Cognoms de l'Usuari</label>
                                    <input type="text" id="surname" v-model="form.surname"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800" 
                                        required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correu Electrònic</label>
                                <input type="email" id="email" v-model="form.email"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800" 
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Contrasenya</label>
                                <input type="password" id="password" v-model="form.password"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800" 
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                                <select id="role" v-model="editUserForm.role"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800 bg-white" 
                                    required>
                                    <option v-for="role in roles" :key="role.id" :value="role.id">
                                        {{ role.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Imatge</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors duration-200">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Pujar un arxiu</span>
                                                <input id="image" type="file" @change="handleUserImageUpload" class="sr-only" />
                                            </label>
                                            <p class="pl-1">o arrossega i deixa anar</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF fins a 10MB</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                                <button type="button" @click="closeModalU"
                                    class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                                    Cancel·lar
                                </button>
                                <button type="submit"
                                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                    Crear Usuari
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal add Event -->
                <div v-if="isModalEventOpen" class="fixed inset-0 flex items-center justify-center z-50">
                    <!-- Overlay con efecto blur -->
                    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
                    
                    <div class="bg-white p-8 rounded-2xl shadow-2xl w-1/2 relative transform transition-all duration-300 scale-100 max-h-[90vh] overflow-y-auto m-4">
                        <div class="border-b border-gray-100 pb-4 mb-6 sticky top-0 bg-white z-10">
                            <h2 class="text-2xl font-bold text-gray-800">Crear Esdeveniment</h2>
                        </div>
                        
                        <form @submit.prevent="addEvent" enctype="multipart/form-data" class="space-y-6 flex flex-col min-h-[calc(100%-2rem)]">
                            <div class="flex-grow space-y-6">
                                <div class="form-group">
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Títol del esdeveniment</label>
                                    <input type="text" id="title" v-model="event.title"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800" 
                                        required />
                                </div>

                                <div class="form-group">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripció del esdeveniment</label>
                                    <textarea id="description" v-model="event.description"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                        rows="4" 
                                        required></textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Data del esdeveniment</label>
                                        <input type="date" id="date" v-model="event.date"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800" 
                                            required />
                                    </div>

                                    <div class="form-group">
                                        <label for="time" class="block text-sm font-medium text-gray-700 mb-2">Hora del esdeveniment</label>
                                        <input type="time" id="time" v-model="event.time"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800" 
                                            required />
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitud</label>
                                        <input type="number" step="any" id="longitude" v-model="event.longitude"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800" 
                                            required />
                                    </div>

                                    <div class="form-group">
                                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitud</label>
                                        <input type="number" step="any" id="latitude" v-model="event.latitude"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800" 
                                            required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Imatge</label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors duration-200">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Pujar un arxiu</span>
                                                    <input id="image" type="file" @change="handleFileUpload" class="sr-only" accept="image/*" />
                                                </label>
                                                <p class="pl-1">o arrossega i deixa anar</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF fins a 10MB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-auto pt-6 border-t border-gray-100">
                                <div class="flex justify-end space-x-4">
                                    <button type="button" @click="closeModalE"
                                        class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                                        Cancel·lar
                                    </button>
                                    <button type="submit"
                                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                        Crear Esdeveniment
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal edit user -->
                <div v-if="isEditUserModalOpen" class="fixed inset-0 flex items-center justify-center z-50">
                    <div class="bg-white p-8 rounded-lg shadow-lg w-1/2">
                        <h2 class="text-xl font-semibold mb-2">Editar Usuario</h2>
                        <form @submit.prevent="updateUser">
                            <div>
                                <label for="name" class="block text-black">Nom de l'Usuari:</label>
                                <input type="text" id="name" v-model="editUserForm.name"
                                    class="border rounded w-full p-2 text-black" required />
                            </div>
                            <div>
                                <label for="surname" class="block text-black">Cognoms de l'Usuari:</label>
                                <input type="text" id="surname" v-model="editUserForm.surname"
                                    class="border rounded w-full p-2 text-black" required />
                            </div>
                            <div class="mt-4">
                                <label for="email" class="block text-black">Correu Electrònic:</label>
                                <input type="email" id="email" v-model="editUserForm.email"
                                    class="border rounded w-full p-2 text-black" required />
                            </div>
                            <div class="mt-4">
                                <label for="password" class="block text-black">Contrasenya:</label>
                                <input type="password" id="password" v-model="editUserForm.password"
                                    placeholder="Deixar en blanc si no vols canviar la contrassenya"
                                    class="border rounded w-full p-2 text-black" />
                            </div>
                            <div class="mt-4">
                                <label for="role" class="block text-black">Rol:</label>
                                <select id="role" v-model="editUserForm.role"
                                    class="border rounded w-full p-2 text-black" required>
                                    <option v-for="role in roles" :key="role.id" :value="role.id">
                                        {{ role.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="bg-SubastaButton1 text-white px-4 py-2 rounded">
                                    Actualitzar
                                </button>
                                <button type="button" @click="closeEditUserModal"
                                    class="bg-red-500 text-white px-4 py-2 rounded ml-4">
                                    Cancel·lar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>

<style scoped>
.btn-gradient {
    transition: all 0.3s ease;
}

.btn-gradient:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Efectos hover */
.hover-scale {
    transition: transform 0.3s ease;
}

.hover-scale:hover {
    transform: scale(1.05);
}
</style>