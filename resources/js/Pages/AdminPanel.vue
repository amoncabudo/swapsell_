<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { ref, onMounted, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { Events } from 'leaflet';
import AOS from 'aos';
import 'aos/dist/aos.css';
import { Head } from '@inertiajs/vue3';
import Cookies from "@/Components/Cookies.vue";

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
const searchQuery = ref('');
const selectedCategory = ref('');
const sortBy = ref('recent');
const categories = ref([]);
const isEditProductModalOpen = ref(false);
const isEditEventModalOpen = ref(false);

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
    { id: 1, name: 'Manager' },
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
const editProductForm = useForm({
    id: '',
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
    role: '',
    image: null
});
const closeModalU = () => { //Close modal for add user
    isModalUserOpen.value = false;
    form.reset();
};

//Function for add user
function addUser() {
    form.post(route('users'), {
        forceFormData: true,
        onSuccess: () => {
            closeModalU();
            // Update the users list
            axios.get(route('users.list'))
                .then(response => {
                    users.value = response.data;
                    toastMessage.value = 'Usuario creado correctamente';
                    toastType.value = 'success';
                    showToast.value = true;
                })
                .catch(error => {
                    console.error('Error al actualizar la lista:', error);
                });
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        },
        onError: () => {
            toastMessage.value = 'Error al crear el usuario';
            toastType.value = 'error';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        }
    });
}//Modal Events
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
            closeModalU();
            // Actualizar lista de usuarios
            axios.get(route('users.list'))
                .then(response => {
                    users.value = response.data;
                    toastMessage.value = 'Usuario creado correctamente';
                    toastType.value = 'success';
                    showToast.value = true;
                })
                .catch(error => {
                    console.error('Error al actualizar la lista:', error);
                });
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
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

//Function for delete Event
const deleteEvent = async (eventId) => { //Delete Event
    if (confirm('¿Estás segur de que vols esborrar aquest esdeveniment?')) { //Confirmation for delete Event
        try {
            await axios.delete(route('events.delete', eventId)); //Delete request for delete Event
            // Update the users list after deleting one
            events.value = events.value.filter(event => event.id !== eventId);
            toastMessage.value = 'Esdeveniment eliminat correctament'; //Toast message for delete Event
            toastType.value = 'success'; //Toast type for delete Event
            showToast.value = true; //Show toast for delete Event
            setTimeout(() => {
                showToast.value = false; //Hide toast for delete Event
            }, 3000); //Hide toast for delete Event
        } catch (error) {
            console.error('Error al eliminar el esdeveniment:', error); //Error for delete Event
            toastMessage.value = 'Error al eliminar l\'esdeveniment'; //Toast message for delete Event
            toastType.value = 'error'; //Toast type for delete Event
            showToast.value = true; //Show toast for delete Event
            setTimeout(() => {
                showToast.value = false; //Hide toast for delete Event
            }, 3000); //Hide toast for delete Event
        }
    }
};

// Function to load events
const loadEvents = async () => {
    try {
        const response = await axios.get(route('events.list'));
        events.value = response.data;
    } catch (error) {
        console.error('Error al cargar esdeveniments:', error);
        toastMessage.value = 'Error al cargar els esdeveniments';
        toastType.value = 'error';
        showToast.value = true;
        setTimeout(() => {
            showToast.value = false;
        }, 3000);
    }
};

// Function to update event
function updateEvent() {
    const eventData = {
        id: editEventForm.id,
        title: editEventForm.title,
        description: editEventForm.description,
        date: editEventForm.date,
        time: editEventForm.time,
        longitude: editEventForm.longitude,
        latitude: editEventForm.latitude,
        status: editEventForm.status
    };

    axios.put(route('events.update', editEventForm.id), eventData)
        .then(async response => {
            closeEditEventModal();
            // Reload the events list
            await loadEvents();

            toastMessage.value = 'Esdeveniment actualitzat correctament!';
            toastType.value = 'success';
            showToast.value = true;

            // Clean the form
            editEventForm.reset();

            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        })
        .catch(error => {
            console.error('Error:', error);
            toastMessage.value = 'Error al actualitzar l\'esdeveniment';
            toastType.value = 'error';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
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

// Function to load users
const loadUsers = async () => {
    try {
        const response = await axios.get(route('users.list'));
        users.value = response.data;
    } catch (error) {
        console.error('Error al cargar usuarios:', error);
        toastMessage.value = 'Error al cargar els usuaris';
        toastType.value = 'error';
        showToast.value = true;
        setTimeout(() => {
            showToast.value = false;
        }, 3000);
    }
};

// Function to update user
function updateUser() {
    const userData = {
        name: editUserForm.name,
        surname: editUserForm.surname,
        email: editUserForm.email,
        role: editUserForm.role
    };

    if (editUserForm.password) {
        userData.password = editUserForm.password;
    }

    axios.put(route('users.update', editUserForm.id), userData)
        .then(async response => {
            closeEditUserModal();
            // Reload the users list
            await loadUsers();

            toastMessage.value = 'Usuari actualitzat correctament!';
            toastType.value = 'success';
            showToast.value = true;

            // Clean the form
            editUserForm.reset();

            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        })
        .catch(error => {
            console.error('Error:', error);
            toastMessage.value = 'Error al actualitzar l\'usuari';
            toastType.value = 'error';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
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
        // Load users
        const responseUsers = await axios.get(route('users.list'));
        users.value = responseUsers.data;

        // Load products
        const responseProducts = await axios.get(route('products.list'));
        // Filter out products with category_id 7
        products.value = responseProducts.data.filter(product => product.category_id !== 7);

        // Load events
        const responseEvents = await axios.get(route('events.list'));
        events.value = responseEvents.data;

    } catch (error) {
        console.error('Error al cargar les dades:', error.response ? error.response.data : error);
        toastMessage.value = 'Error al cargar les dades';
        toastType.value = 'error';
        showToast.value = true;
        setTimeout(() => {
            showToast.value = false;
        }, 3000);
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

// Function to load products
const loadProducts = async () => {
    try {
        const response = await axios.get(route('products.list'));
        // Filter out products with category_id 7
        products.value = response.data.filter(product => product.category_id !== 7);
    } catch (error) {
        console.error('Error al cargar productes:', error);
        toastMessage.value = 'Error al cargar els productes';
        toastType.value = 'error';
        showToast.value = true;
        setTimeout(() => {
            showToast.value = false;
        }, 3000);
    }
};

// Function to add product
function addProduct() {
    const formData = new FormData();
    for (const key in product.value) {
        formData.append(key, product.value[key]);
    }

    axios.post(route('products.addProduct'), formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(async response => {
            closeModalP();
            await loadProducts(); // Reload the products list

            // Limpiar el formulario
            product.value = {
                name: '',
                description: '',
                price: '',
                longitude: '',
                latitude: '',
                status: 'active',
                category_id: '1',
                image: null
            };

            toastMessage.value = 'Producte creat correctament!';
            toastType.value = 'success';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        })
        .catch(error => {
            console.error('Error al crear el producte:', error.response?.data);
            toastMessage.value = 'Error al crear el producte';
            toastType.value = 'error';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        });
}

// Function to update product
function updateProduct() {
    const formData = new FormData();
    formData.append('name', editProductForm.name);
    formData.append('description', editProductForm.description);
    formData.append('price', editProductForm.price);
    formData.append('longitude', editProductForm.longitude);
    formData.append('latitude', editProductForm.latitude);
    formData.append('category_id', editProductForm.category_id);
    formData.append('status', editProductForm.status);
    
    if (editProductForm.image) {
        formData.append('image', editProductForm.image);
    }

    axios.post(route('products.adminUpdate', editProductForm.id), formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        },
        _method: 'PUT'
    })
    .then(async response => {
        closeEditProductModal();
        await loadProducts();

        toastMessage.value = 'Producte actualitzat correctament!';
        toastType.value = 'success';
        showToast.value = true;

        editProductForm.reset();

        setTimeout(() => {
            showToast.value = false;
        }, 3000);
    })
    .catch(error => {
        console.error('Error:', error);
        toastMessage.value = 'Error al actualitzar el producte';
        toastType.value = 'error';
        showToast.value = true;
        setTimeout(() => {
            showToast.value = false;
        }, 3000);
    });
}

//Function for delete product
const deleteProduct = async (productId) => { //Delete product
    if (confirm('¿Estás segur de que vols esborrar aquest producte?')) { //Confirmation for delete product
        try {
            await axios.delete(route('products.delete', productId)); //Delete request for delete product
            // Update the users list after deleting one
            products.value = products.value.filter(product => product.id !== productId);
            toastMessage.value = 'Producte eliminat correctament'; //Toast message for delete product
            toastType.value = 'success'; //Toast type for delete product
            showToast.value = true; //Show toast for delete product
            setTimeout(() => {
                showToast.value = false; //Hide toast for delete product
            }, 3000); //Hide toast for delete product
        } catch (error) {
            console.error('Error al eliminar el producte:', error); //Error for delete product
            toastMessage.value = 'Error al eliminar el producte'; //Toast message for delete product
            toastType.value = 'error'; //Toast type for delete product
            showToast.value = true; //Show toast for delete product
            setTimeout(() => {
                showToast.value = false; //Hide toast for delete product
            }, 3000); //Hide toast for delete product
        }
    }
};

// Función para abrir el modal de edición
const openEditProductModal = (product) => {
    editProductForm.id = product.id;
    editProductForm.name = product.name;
    editProductForm.description = product.description;
    editProductForm.price = product.price;
    editProductForm.longitude = product.longitude;
    editProductForm.latitude = product.latitude;
    editProductForm.category_id = product.category_id;
    editProductForm.status = product.status;
    editProductForm.image = null;
    isEditProductModalOpen.value = true;
};

// Function to close the edit product modal
const closeEditProductModal = () => {
    isEditProductModalOpen.value = false;
    editProductForm.reset();
};

const editEventForm = useForm({
    id: '',
    title: '',
    description: '',
    date: '',
    time: '',
    longitude: '',
    latitude: '',
    image: null
});

const openEditEventModal = (event) => {
    editEventForm.id = event.id;
    editEventForm.title = event.title;
    editEventForm.description = event.description;
    editEventForm.date = event.date;
    editEventForm.time = event.time;
    editEventForm.longitude = event.longitude;
    editEventForm.latitude = event.latitude;
    editEventForm.image = null;
    isEditEventModalOpen.value = true;
};

const closeEditEventModal = () => {
    isEditEventModalOpen.value = false;
    editEventForm.reset();
};

const handleUserImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
    }
};


const searchedUsers = computed(() => { // Computed for search users
    if (!searchQuery.value) { // If the search query is empty, returns the list of users
        return users.value; // Returns the list of users
    }
    return users.value.filter(user => // Returns the filtered list of users
        user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || // If the user's name includes the search query, returns the user
        user.surname.toLowerCase().includes(searchQuery.value.toLowerCase()) || // If the user's surname includes the search query, returns the user
        user.email.toLowerCase().includes(searchQuery.value.toLowerCase()) // If the user's email includes the search query, returns the user
    );
});

// Function to view product details
const viewProductDetails = (productId) => {
    window.location.href = route('product.show', { id: productId }); // Redirect to the product details page
};

const viewEventDetails = (eventId) => {
    window.location.href = route('eventInfo', { id: eventId }); // Redirect to the event details page
};

function handleEditProductImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
        editProductForm.image = file;
    }
}

</script>

<template>

    <Head title="Adminpanel"></Head>
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
                    <div class="bg-white rounded-2xl shadow-xl p-8 transform hover:scale-105 transition-all duration-300 border border-gray-100"
                        data-aos="fade-right">
                        <div
                            class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold mb-4 text-gray-800 text-center">Usuaris</h2>
                        <p class="text-gray-600 text-center mb-8">Gestiona els usuaris del sistema</p>
                        <button @click="openModalU"
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-4 rounded-xl hover:shadow-lg flex items-center justify-center gap-3 group transition-all duration-300 hover:-translate-y-1">
                            <span class="text-lg">Afegir Usuari</span>
                            <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>

                    <!-- Event Card -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 transform hover:scale-105 transition-all duration-300 border border-gray-100"
                        data-aos="fade-up">
                        <div
                            class="bg-gradient-to-r from-green-500 to-green-600 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M8 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1h2a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold mb-4 text-gray-800 text-center">Esdeveniments</h2>
                        <p class="text-gray-600 text-center mb-8">Gestiona els esdeveniments del sistema</p>
                        <button @click="openModalE"
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-4 rounded-xl hover:shadow-lg flex items-center justify-center gap-3 group transition-all duration-300 hover:-translate-y-1">
                            <span class="text-lg">Afegir Esdeveniment</span>
                            <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>

                    <!-- Product Card -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 transform hover:scale-105 transition-all duration-300 border border-gray-100"
                        data-aos="fade-left">
                        <div
                            class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M14 7h-4v3a1 1 0 0 1-2 0V7H6a1 1 0 0 0-.997.923l-.917 11.924A2 2 0 0 0 6.08 22h11.84a2 2 0 0 0 1.994-2.153l-.917-11.924A1 1 0 0 0 18 7h-2v3a1 1 0 1 1-2 0V7Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold mb-4 text-gray-800 text-center">Productes</h2>
                        <p class="text-gray-600 text-center mb-8">Gestiona els productes del sistema</p>
                        <button @click="openModalP"
                            class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white py-4 rounded-xl hover:shadow-lg flex items-center justify-center gap-3 group transition-all duration-300 hover:-translate-y-1">
                            <span class="text-lg">Afegir Producte</span>
                            <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Users List Section -->
                <div class="mt-16" data-aos="fade-up">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        <!-- Header de la sección -->
                        <div class="p-8 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <h2 class="text-3xl font-bold text-gray-800">Llista d'Usuaris</h2>
                                <p class="text-gray-600 mt-1">Gestiona els usuaris del sistema</p>
                            </div>
                            <div class="flex gap-4">
                                <div class="relative">
                                    <label for="search-users" class="sr-only">Cercar usuaris</label>
                                    <input id="search-users" type="text" placeholder="Cercar usuaris..."
                                        v-model="searchQuery"
                                        class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800"
                                        aria-label="Cercar usuaris">
                                    <svg class="w-5 h-5 text-gray-500 absolute left-3 top-1/2 transform -translate-y-1/2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Users table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            Usuari</th>
                                        <th
                                            class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            Email</th>
                                        <th
                                            class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            Rol</th>
                                        <th
                                            class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            Estat</th>
                                        <th
                                            class="px-8 py-5 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            Accions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="user in searchedUsers" :key="user.id"
                                        class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-8 py-5 whitespace-nowrap">
                                            <div class="flex items-center space-x-4">
                                                <img :src="user.image ? `/storage/${user.image}` : '/images/default-avatar.png'"
                                                    :alt="user.name"
                                                    class="h-10 w-10 rounded-full object-cover border-2 border-gray-200">
                                                <div>
                                                    <p class="font-semibold text-gray-800">{{ user.name }} {{
                                                        user.surname }}</p>
                                                    <p class="text-sm text-gray-600">{{ user.email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ user.email }}</div>
                                        </td>
                                        <td class="px-8 py-5 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-green-100 text-green-800': user.role === 0,
                                                    'bg-blue-100 text-blue-800': user.role === 1,
                                                    'bg-purple-100 text-purple-800': user.role === 2
                                                }">
                                                {{ roles.find(role => role.id === user.role)?.name || 'Desconegut'
                                                }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Actiu
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-3">
                                                <button @click="openEditUserModal(user)"
                                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                                    aria-label="Editar Usuari">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                <button @click="deleteUser(user.id)"
                                                    class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                                    aria-label="Eliminar Usuari">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
                <!-- Products List Section -->
                <div class="mt-16" data-aos="fade-up">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        <div class="p-8 border-b border-gray-100">
                            <h2 class="text-3xl font-bold text-gray-800">Llista de Productes</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            Nom</th>
                                        <th
                                            class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            Descripció</th>
                                        <th
                                            class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            Preu</th>
                                        <th
                                            class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            Estat</th>
                                        <th
                                            class="px-8 py-5 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            Accions</th>

                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="product in products" :key="product.id"
                                        class="hover:bg-gray-50 transition-colors">
                                        <td class="px-8 py-5">
                                            <div class="text-sm font-medium text-gray-800">{{ product.name }}</div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="text-sm text-gray-800">
                                                <p class="truncate w-60">{{ product.description }}</p>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="text-sm text-gray-800">{{ product.price }} €</div>
                                        </td>
                                        <td class="px-8 py-5 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-red-100 text-red-900': product.status === 0,
                                                    'bg-green-100 text-green-800': product.status === 1
                                                }">
                                                {{ product.status === 0 ? 'Venut' : 'Disponible' }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-3">
                                                <button @click="openEditProductModal(product)"
                                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                                    aria-label="Editar Producte">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                <button @click="deleteProduct(product.id)"
                                                    class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                                    aria-label="Eliminar Producte">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                                <button @click="viewProductDetails(product.id)"
                                                    class="text-green-600 hover:text-green-900 transition-colors duration-200 "
                                                    aria-label="Veure detalls del producte">
                                                    <svg class="w-5 h-5 text-gray-700 hover:text-black dark:text-white"
                                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-width="2"
                                                            d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                                        <path stroke="currentColor" stroke-width="2"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>

                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Events List Section -->
                <div class="mt-16" data-aos="fade-up">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        <div class="p-8 border-b border-gray-100">
                            <h2 class="text-3xl font-bold text-gray-800">Llista d'Esdeveniments</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            Titol</th>
                                        <th
                                            class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            Descripció</th>
                                        <th
                                            class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            Data Esdeveniment</th>
                                        <th
                                            class="px-8 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            Hora Esdeveniment</th>
                                        <th
                                            class="px-8 py-5 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                            Accions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="event in events" :key="event.id"
                                        class="hover:bg-gray-50 transition-colors">
                                        <td class="px-8 py-5">
                                            <div class="text-sm font-medium text-gray-800">{{ event.title }}</div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="text-sm text-gray-800">{{ event.description }}</div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="text-sm text-gray-800">{{ event.date }}</div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="text-sm text-gray-800">{{ event.time }}</div>
                                        </td>
                                        <td class="px-8 py-5 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-3">
                                                <button @click="openEditEventModal(event)"
                                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                                    aria-label="Editar Esdeveniment">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                <button @click="deleteEvent(event.id)"
                                                    class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                                    aria-label="Eliminar Esdeveniment">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                                <button @click="viewEventDetails(event.id)"
                                                    class="text-green-600 hover:text-green-900 transition-colors duration-200"
                                                    aria-label="Veure detalls del producte">
                                                    <svg class="w-5 h-5 text-gray-700 hover:text-black dark:text-white"
                                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-width="2"
                                                            d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                                        <path stroke="currentColor" stroke-width="2"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>

                                                </button>
                                            </div>
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

                    <div
                        class="bg-white p-8 rounded-2xl shadow-2xl w-1/2 relative transform transition-all duration-300 scale-100">
                        <div class="border-b border-gray-100 pb-4 mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Crear Usuari</h2>
                        </div>

                        <form @submit.prevent="addUser" enctype="multipart/form-data" class="space-y-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom de
                                        l'Usuari</label>
                                    <input type="text" id="name" v-model="form.name"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                        required />
                                </div>

                                <div class="form-group">
                                    <label for="surname" class="block text-sm font-medium text-gray-700 mb-2">Cognoms de
                                        l'Usuari</label>
                                    <input type="text" id="surname" v-model="form.surname"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                        required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correu
                                    Electrònic</label>
                                <input type="email" id="email" v-model="form.email"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="password"
                                    class="block text-sm font-medium text-gray-700 mb-2">Contrasenya</label>
                                <input type="password" id="password" v-model="form.password"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                                <select id="role" v-model="form.role"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                    required>
                                    <option v-for="role in roles" :key="role.id" :value="role.id">
                                        {{ role.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Imatge</label>
                                <div
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors duration-200">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                            viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="image"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Pujar un arxiu</span>
                                                <input id="image" type="file" @change="handleUserImageUpload"
                                                    class="sr-only" />
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
                    <!-- Overlay with blur effect -->
                    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

                    <div
                        class="bg-white p-8 rounded-2xl shadow-2xl w-1/2 relative transform transition-all duration-300 scale-100 max-h-[90vh] overflow-y-auto m-4">
                        <div class="border-b border-gray-100 pb-4 mb-6 sticky top-0 bg-white z-10">
                            <h2 class="text-2xl font-bold text-gray-800">Crear Esdeveniment</h2>
                        </div>

                        <form @submit.prevent="addEvent" enctype="multipart/form-data"
                            class="space-y-6 flex flex-col min-h-[calc(100%-2rem)]">
                            <div class="flex-grow space-y-6">
                                <div class="form-group">
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Títol
                                        del
                                        esdeveniment</label>
                                    <input type="text" id="title" v-model="event.title"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                        required />
                                </div>

                                <div class="form-group">
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 mb-2">Descripció del
                                        esdeveniment</label>
                                    <textarea id="description" v-model="event.description"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                        rows="4" required></textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Data
                                            del
                                            esdeveniment</label>
                                        <input type="date" id="date" v-model="event.date"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required />
                                    </div>

                                    <div class="form-group">
                                        <label for="time" class="block text-sm font-medium text-gray-700 mb-2">Hora
                                            del
                                            esdeveniment</label>
                                        <input type="time" id="time" v-model="event.time"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required />
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-6">

                                    <div class="form-group">
                                        <label for="latitude"
                                            class="block text-sm font-medium text-gray-700 mb-2">Latitud</label>
                                        <input type="number" step="any" id="latitude" v-model="event.latitude"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required />
                                    </div>

                                    <div class="form-group">
                                        <label for="longitude"
                                            class="block text-sm font-medium text-gray-700 mb-2">Longitud</label>
                                        <input type="number" step="any" id="longitude" v-model="event.longitude"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required />
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="image"
                                        class="block text-sm font-medium text-gray-700 mb-2">Imatge</label>
                                    <div
                                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors duration-200">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                fill="none" viewBox="0 0 48 48">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="image"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Pujar un arxiu</span>
                                                    <input id="image" type="file" @change="handleFileUpload"
                                                        class="sr-only" accept="image/*" />
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

                <!-- Modal add Product -->
                <div v-if="isModalProductOpen" class="fixed inset-0 flex items-center justify-center z-50">
                    <!-- Overlay with blur effect -->
                    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

                    <div
                        class="bg-white p-8 rounded-2xl shadow-2xl w-1/2 relative transform transition-all duration-300 scale-100 max-h-[90vh] overflow-y-auto m-4">
                        <div class="border-b border-gray-100 pb-4 mb-6 sticky top-0 bg-white z-10">
                            <h2 class="text-2xl font-bold text-gray-800">Crear Producte</h2>
                        </div>

                        <form @submit.prevent="addProduct" enctype="multipart/form-data"
                            class="space-y-6 flex flex-col min-h-[calc(100%-2rem)]">
                            <div class="flex-grow space-y-6">
                                <div class="form-group">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom del
                                        producte</label>
                                    <input type="text" id="name" v-model="product.name"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                        required />
                                </div>

                                <div class="form-group">
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 mb-2">Descripció del
                                        producte</label>
                                    <textarea id="description" v-model="product.description"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                        rows="4" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Preu del
                                        producte</label>
                                    <input type="number" id="price" v-model="product.price"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                        required />
                                </div>

                                <div class="grid grid-cols-2 gap-6">

                                    <div class="form-group">
                                        <label for="latitude"
                                            class="block text-sm font-medium text-gray-700 mb-2">Latitud</label>
                                        <input type="number" step="any" id="latitude" v-model="product.latitude"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required />
                                    </div>

                                    <div class="form-group">
                                        <label for="longitude"
                                            class="block text-sm font-medium text-gray-700 mb-2">Longitud</label>
                                        <input type="number" step="any" id="longitude" v-model="product.longitude"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required />
                                    </div>


                                </div>

                                <div class="form-group">
                                    <label for="image"
                                        class="block text-sm font-medium text-gray-700 mb-2">Imatge</label>
                                    <div
                                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors duration-200">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                fill="none" viewBox="0 0 48 48">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="image"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Pujar un arxiu</span>
                                                    <input id="image" type="file" @change="handleProductImageUpload"
                                                        class="sr-only" accept="image/*" />
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
                                    <button type="button" @click="closeModalP"
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
                <div v-if="isEditUserModalOpen" class="fixed inset-0 flex items-center justify-center z-50 px-4 sm:px-0"
                    @click.self="closeEditUserModal">
                    <!-- Overlay with improved blur effect -->
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-900/50 to-black/50 backdrop-blur-md">
                    </div>

                    <!-- Modal container with animation -->
                    <div
                        class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-2xl relative transform transition-all duration-300 scale-100 hover:scale-[1.02]">
                        <!-- Header with improved design -->
                        <div class="flex items-center justify-between border-b border-gray-100 pb-6 mb-8">
                            <div>
                                <h2 class="text-3xl font-bold text-gray-800">
                                    Editar Usuari
                                </h2>
                                <p class="text-sm text-gray-600 mt-1">
                                    Modifica la informació de l'usuari
                                </p>
                            </div>
                            <button @click="closeEditUserModal"
                                class="p-2 hover:bg-gray-100 rounded-full transition-colors duration-200">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="updateUser" class="space-y-6">
                            <!-- Grid for name and surname -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="form-group space-y-2">
                                    <label for="name" class="block text-sm font-semibold text-gray-800">
                                        Nom de l'Usuari
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="name" v-model="editUserForm.name"
                                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required />
                                    </div>
                                </div>

                                <div class="form-group space-y-2">
                                    <label for="surname" class="block text-sm font-semibold text-gray-800">
                                        Cognoms de l'Usuari
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="surname" v-model="editUserForm.surname"
                                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required />
                                    </div>
                                </div>
                            </div>

                            <!-- Email field -->
                            <div class="form-group space-y-2">
                                <label for="email" class="block text-sm font-semibold text-gray-800">
                                    Correu Electrònic
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                    </div>
                                    <input type="email" id="email" v-model="editUserForm.email"
                                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                        required />
                                </div>
                            </div>

                            <!-- Password field -->
                            <div class="form-group space-y-2">
                                <label for="password" class="block text-sm font-semibold text-gray-800">
                                    Contrasenya
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input type="password" id="password" v-model="editUserForm.password"
                                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                        placeholder="Deixar en blanc si no vols canviar la contrasenya" />
                                </div>
                                <p class="text-xs text-gray-600 mt-1">
                                    Deixa aquest camp buit si no vols canviar la contrasenya
                                </p>
                            </div>

                            <!-- Role selector -->
                            <div class="form-group space-y-2">
                                <label for="role" class="block text-sm font-semibold text-gray-800">
                                    Rol
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <select id="role" v-model="editUserForm.role"
                                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 appearance-none text-gray-800"
                                        required>
                                        <option v-for="role in roles" :key="role.id" :value="role.id">
                                            {{ role.name }}
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Improved buttons with effects -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                                <button type="button" @click="closeEditUserModal"
                                    class="px-6 py-2.5 border-2 border-red-300 rounded-xl text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                                    Cancel·lar
                                </button>
                                <button type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-blue-500/50">
                                    Actualitzar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal edit product -->
                <div v-if="isEditProductModalOpen"
                    class="fixed inset-0 flex items-center justify-center z-50 px-4 sm:px-0"
                    @click.self="closeEditProductModal">
                    <!-- Overlay with improved blur effect -->
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-900/50 to-black/50 backdrop-blur-md">
                    </div>

                    <!-- Modal container with animation -->
                    <div
                        class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-2xl relative transform transition-all duration-300 scale-100 hover:scale-[1.02]">
                        <!-- Header with improved design -->
                        <div class="flex items-center justify-between border-b border-gray-100 pb-6 mb-8">
                            <div>
                                <h2 class="text-3xl font-bold text-gray-800">
                                    Editar Producte
                                </h2>
                                <p class="text-sm text-gray-600 mt-1">
                                    Modifica la informació del producte
                                </p>
                            </div>
                            <button @click="closeEditProductModal"
                                class="p-2 hover:bg-gray-100 rounded-full transition-colors duration-200">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="updateProduct" class="space-y-6">
                            <!-- Grid for name and description -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="form-group space-y-2">
                                    <label for="name" class="block text-sm font-semibold text-gray-800">
                                        Nom del producte
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        <input type="text" id="name" v-model="editProductForm.name"
                                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required />
                                    </div>
                                </div>

                                <div class="form-group space-y-2">
                                    <label for="price" class="block text-sm font-semibold text-gray-800">
                                        Preu del producte
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <input type="number" id="price" v-model="editProductForm.price"
                                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required />
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="form-group space-y-2">
                                <label for="description" class="block text-sm font-semibold text-gray-800">
                                    Descripció del producte
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6h16M4 12h16M4 18h7" />
                                        </svg>
                                    </div>
                                    <textarea id="description" v-model="editProductForm.description"
                                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-gray-800 min-h-[100px]"
                                        required></textarea>
                                </div>
                            </div>

                            <!-- Grid for longitude and latitude -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="form-group space-y-2">
                                    <label for="longitude" class="block text-sm font-semibold text-gray-800">
                                        Longitud
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                            </svg>
                                        </div>
                                        <input type="number" id="longitude" v-model="editProductForm.longitude"
                                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required step="any" />
                                    </div>
                                </div>

                                <div class="form-group space-y-2">
                                    <label for="latitude" class="block text-sm font-semibold text-gray-800">
                                        Latitud
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <input type="number" id="latitude" v-model="editProductForm.latitude"
                                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required step="any" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                    <label for="image"
                                        class="block text-sm font-medium text-gray-700 mb-2">Imatge</label>
                                    <div
                                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors duration-200">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                fill="none" viewBox="0 0 48 48">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="image"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Pujar un arxiu</span>
                                                    <input id="image" type="file" @change="handleEditProductImageUpload"
                                                        class="sr-only" accept="image/*" />
                                                </label>
                                                <p class="pl-1">o arrossega i deixa anar</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF fins a 10MB</p>
                                        </div>
                                    </div>
                                </div>

                            <!-- Improved buttons with effects -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                                <button type="button" @click="closeEditProductModal"
                                    class="px-6 py-2.5 border-2 border-red-300 rounded-xl text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                                    Cancel·lar
                                </button>
                                <button type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-blue-500/50">
                                    Actualitzar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal edit event -->
                <div v-if="isEditEventModalOpen"
                    class="fixed inset-0 flex items-center justify-center z-50 px-4 sm:px-0"
                    @click.self="closeEditEventModal">
                    <!-- Overlay with improved blur effect -->
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-900/50 to-black/50 backdrop-blur-md">
                    </div>

                    <!-- Modal container with animation -->
                    <div
                        class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-2xl relative transform transition-all duration-300 scale-100 hover:scale-[1.02]">
                        <!-- Header with improved design -->
                        <div class="flex items-center justify-between border-b border-gray-100 pb-6 mb-8">
                            <div>
                                <h2 class="text-3xl font-bold text-gray-800">
                                    Editar Esdeveniment
                                </h2>
                                <p class="text-sm text-gray-600 mt-1">
                                    Modifica la informació de l'esdeveniment
                                </p>
                            </div>
                            <button @click="closeEditEventModal"
                                class="p-2 hover:bg-gray-100 rounded-full transition-colors duration-200">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="updateEvent" class="space-y-6">
                            <!-- Grid for title and description -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="form-group space-y-2">
                                    <label for="title" class="block text-sm font-semibold text-gray-800">
                                        Títol de l'Esdeveniment
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="title" v-model="editEventForm.title"
                                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required />
                                    </div>
                                </div>

                                <div class="form-group space-y-2">
                                    <label for="description" class="block text-sm font-semibold text-gray-800">
                                        Descripció de l'Esdeveniment
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 6h16M4 12h16M4 18h7" />
                                            </svg>
                                        </div>
                                        <input type="text" id="description" v-model="editEventForm.description"
                                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required />
                                    </div>
                                </div>
                            </div>

                            <!-- Grid for date and time -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="form-group space-y-2">
                                    <label for="date" class="block text-sm font-semibold text-gray-800">
                                        Data de l'Esdeveniment
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input type="date" id="date" v-model="editEventForm.date"
                                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required />
                                    </div>
                                </div>

                                <div class="form-group space-y-2">
                                    <label for="time" class="block text-sm font-semibold text-gray-800">
                                        Hora de l'Esdeveniment
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <input type="time" id="time" v-model="editEventForm.time"
                                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required />
                                    </div>
                                </div>
                            </div>

                            <!-- Grid for longitude and latitude -->
                            <div class="form-group space-y-2">
                                <label for="latitude" class="block text-sm font-semibold text-gray-800">
                                    Latitud
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <input type="number" id="latitude" v-model="editEventForm.latitude"
                                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                        required step="any" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="form-group space-y-2">
                                    <label for="longitude" class="block text-sm font-semibold text-gray-800">
                                        Longitud
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                            </svg>
                                        </div>
                                        <input type="number" id="longitude" v-model="editEventForm.longitude"
                                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-gray-800"
                                            required step="any" />
                                    </div>
                                </div>


                            </div>

                            <!-- Improved buttons with effects -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                                <button type="button" @click="closeEditEventModal"
                                    class="px-6 py-2.5 border-2 border-red-300 rounded-xl text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                                    Cancel·lar
                                </button>
                                <button type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-blue-500/50">
                                    Actualitzar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </component>
    <Cookies />
</template>

<style scoped>
.btn-gradient {
    transition: all 0.3s ease;
}

.btn-gradient:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Hover effects */
.hover-scale {
    transition: transform 0.3s ease;
}

.hover-scale:hover {
    transform: scale(1.05);
}
</style>