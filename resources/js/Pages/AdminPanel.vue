<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { ref, onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { Events } from 'leaflet';
import AOS from 'aos';
import 'aos/dist/aos.css';


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

const categoryData = ref({
    labels: [],
    datasets: [
        {
            label: 'Productos por Categoría',
            backgroundColor: '#42A5F5',
            data: []
        }
    ]
});

const editUserForm = useForm({
    id: '',
    name: '',
    surname: '',
    email: '',
    password: '',
    role: '',
    image: null
});

const roles = [
    { id: 0, name: 'Administrador' },
    { id: 1, name: 'Scrum Manager' },
    { id: 2, name: 'Usuario' }
];

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
    axios.post(route('users'), form)
        .then(response => {
            closeModalU();
            toastMessage.value = 'Usuari creat correctament!';
            toastType.value = 'success';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        })
        .catch(error => {
            toastMessage.value = 'Error al crear usuari';
            toastType.value = 'error';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        });
}

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
    image: null
});
const closeModalE = () => {
    isModalEventOpen.value = false;
};

function addEvent() {
    event.post(route('event.addEvent'), {
        onSuccess: () => {
            closeModalE();
            // Actualizar la lista de eventos después de crear uno nuevo
            axios.get(route('event.addEvent')).then(response => {
                events.value = response.data;
            });
            toastMessage.value = 'Event creat correctament!';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        },
        onError: () => {
            toastMessage.value = 'Error al crear event';
            toastType.value = 'error';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        }
    });
}
// Modal User Edit
const openEditUserModal = (user) => {
    editUserForm.id = user.id;
    editUserForm.name = user.name;
    editUserForm.surname = user.surname;
    editUserForm.email = user.email;
    editUserForm.role = user.role || 'user';
    editUserForm.password = '';
    editUserForm.image = null;
    isEditUserModalOpen.value = true;
};

const closeEditUserModal = () => {
    isEditUserModalOpen.value = false;
    editUserForm.reset();
};

function updateUser() {
    const userData = {
        name: editUserForm.name,
        surname: editUserForm.surname,
        email: editUserForm.email,
        role: editUserForm.role
    };
    // Solo incluir la contraseña si se ha ingresado
    if (editUserForm.password) {
        userData.password = editUserForm.password;
    }

    axios.put(route('users.update', editUserForm.id), userData)
        .then(response => {
            closeEditUserModal();
            // Actualizar la lista de usuarios
            const index = users.value.findIndex(u => u.id === editUserForm.id);
            if (index !== -1) {
                users.value[index] = response.data;
            }
            toastMessage.value = 'Usuari actualitzat correctament!';
            toastType.value = 'success';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        })
        .catch(error => {
            console.error('Error:', error);
            toastMessage.value = 'Error al actualitzar usuari';
            toastType.value = 'error';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        });
}

// Actualiza la función deleteUser para que funcione correctamente
const deleteUser = async (userId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
        try {
            await axios.delete(route('users.destroy', userId));
            // Actualiza la lista de usuarios eliminando el usuario borrado
            users.value = users.value.filter(user => user.id !== userId);
            toastMessage.value = 'Usuari eliminat correctament';
            toastType.value = 'success';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        } catch (error) {
            console.error('Error al eliminar el usuari:', error);
            toastMessage.value = 'Error al eliminar el usuari';
            toastType.value = 'error';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        }
    }
};

onMounted(async () => {
    try {
        // Cargar usuarios
        const responseUsers = await axios.get(route('users.list'));
        users.value = responseUsers.data;

    } catch (error) {
        console.error('Error al cargar los datos:', error.response ? error.response.data : error);
        toastMessage.value = 'Error al cargar los datos';
        toastType.value = 'error';
        showToast.value = true;
        setTimeout(() => {
            showToast.value = false;
        }, 3000);
    }
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'EUR'
    }).format(value);
};


function handleFileUpload(e) {
    const file = e.target.files[0];
    if (file) {
        event.image = file; // Guardamos el archivo correctamente en el formulario
    }
}

function handleProductImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
        product.value.image = file;
    }
}

// Modal add product
const openModalP = () => {
    isModalProductOpen.value = true;
};

const closeModalP = () => {
    isModalProductOpen.value = false;
    // Limpiar el formulario
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

function addProduct() {
    const formData = new FormData();
    for (const key in product.value) {
        formData.append(key, product.value[key]);
    }
    console.log(formData);
    axios.post(route('products.addProduct'), formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            closeModalP(); // Cierra el modal
            product.value.push(response.data); // Agrega el nuevo producto a la lista
            toastMessage.value = 'Producte creat correctament!';
            toastType.value = 'success';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        })
        .catch(error => {
            console.error('Error al crear el producte:', error.response.data);
            toastMessage.value = 'Error al crear el producte';
            toastType.value = 'error';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        });
}
</script>

<template>
    <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
        <div class="min-h-screen bg-gray-100">
            <div class="container mx-auto p-2">
                <h1 class="text-4xl font-bold text-center mb-8 text-gray-800 mt-12">PANELL D'ADMINISTRADOR</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center" data-aos="fade-right">
                        <svg class="mx-auto mb-2 w-12 h-12 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z"
                                clip-rule="evenodd" />
                        </svg>
                        <h2 class="text-xl font-semibold mb-2 text-gray-800">AFEGIR USUARIS</h2>
                        <p class="text-gray-800">Apartat per afegir usuaris</p>
                        <div class="mt-4">
                            <button @click="openModalU" class="bg-SubastaButton1 text-white px-4 py-2 rounded"
                                aria-label="Afegir usuari">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h14m-7 7V5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center" data-aos="fade-right">
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
                            <button @click="openModalE" class="bg-SubastaButton1 text-white px-4 py-2 rounded"
                                aria-label="Afegir esdeveniment">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h14m-7 7V5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center" data-aos="fade-right">
                        <svg class="mx-auto mb-2 w-12 h-12 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M14 7h-4v3a1 1 0 0 1-2 0V7H6a1 1 0 0 0-.997.923l-.917 11.924A2 2 0 0 0 6.08 22h11.84a2 2 0 0 0 1.994-2.153l-.917-11.924A1 1 0 0 0 18 7h-2v3a1 1 0 1 1-2 0V7Zm-2-3a2 2 0 0 0-2 2v1H8V6a4 4 0 0 1 8 0v1h-2V6a2 2 0 0 0-2-2Z"
                                clip-rule="evenodd" />
                        </svg>
                        <h2 class="text-xl font-semibold mb-2 text-gray-800">AFEGIR PRODUCTES</h2>
                        <p class="text-gray-800">Apartat per afegir productes</p>
                        <div class="mt-4">
                            <button @click="openModalP" class="bg-SubastaButton1 text-white px-4 py-2 rounded"
                                aria-label="Afegir producte">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h14m-7 7V5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- New Lists Section -->
                <div class="mt-12 grid grid-cols-1 gap-8">
                    <!-- Users List -->
                    <div class="bg-white p-6 rounded-lg shadow-lg" data-aos="fade-left">
                        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Llista d'Usuaris</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                            Nom</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                            Cognoms</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                            Email</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                            Rol</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                            Accions</th>

                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="ml-2">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ user.name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ user.surname }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ user.email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ roles.find(role => role.id === user.role)?.name || 'Desconegut' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button @click="openEditUserModal(user)"
                                                class="text-indigo-800 hover:text-indigo-900 mr-3">Editar</button>
                                            <button @click="deleteUser(user.id)"
                                                class="text-red-800 hover:text-red-900 ">Eliminar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Modal add user -->
                <div v-if="isModalUserOpen" class="fixed inset-0 flex items-center justify-center z-50">
                    <div class="bg-white p-8 rounded-lg shadow-lg w-1/2">
                        <h2 class="text-xl font-semibold mb-2">Crear Usuario</h2>
                        <form @submit.prevent="addUser" enctype="multipart/form-data">
                            <div>
                                <label for="name" class="block text-black">Nom de l'Usuari:</label>
                                <input type="text" id="name" v-model="form.name"
                                    class="border rounded w-full p-2 text-black" required />
                            </div>
                            <div>
                                <label for="surname" class="block text-black">Cognoms de l'Usuari:</label>
                                <input type="text" id="surname" v-model="form.surname"
                                    class="border rounded w-full p-2 text-black" required />
                            </div>

                            <div class="mt-4">
                                <label for="email" class="block text-black">Correu Electrònic:</label>
                                <input type="email" id="email" v-model="form.email"
                                    class="border rounded w-full p-2 text-black" required />
                            </div>
                            <div>
                                <label for="password" class="block text-black">Contrasenya:</label>
                                <input type="password" id="password" v-model="form.password"
                                    class="border rounded w-full p-2 text-black" required />
                            </div>

                            <div class="mt-4">
                                <label for="role" class="block text-black">Rol:</label>
                                <select id="role" v-model="editUserForm.role"
                                    class="border rounded w-full p-2 text-black" required>
                                    <option v-for="role in roles" :key="role.id" :value="role.id">
                                        {{ roles.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="mt-4">
                                <label for="image" class="block text-black">Imatge:</label>
                                <input type="file" id="image" @change="handleUserImageUpload"
                                    class="border rounded w-full p-2 text-black" />
                            </div>
                            <div class="mt-4">
                                <button type="submit"
                                    class="bg-SubastaButton1 text-white px-4 py-2 rounded">Crear</button>
                                <button type="button" @click="closeModalU"
                                    class="bg-red-500 text-white px-4 py-2 rounded ml-4">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal add product -->
                <div v-if="isModalEventOpen" class="fixed inset-0 flex items-center justify-center z-50">
                    <div class="bg-white p-4 rounded-lg shadow-lg w-1/2">
                        <h2 class="text-lg font-semibold mb-2">Crear Producte</h2>
                        <form @submit.prevent="addEvent" enctype="multipart/form-data">
                            <div>
                                <label for="title" class="block text-black text-sm">Títol del esdeveniment:</label>
                                <input type="text" id="title" v-model="event.title"
                                    class="border rounded w-full p-1 text-black" required />
                            </div>
                            <div>
                                <label for="description" class="block text-black text-sm">Descripció del
                                    esdeveniment:</label>
                                <textarea id="description" v-model="event.description"
                                    class="border rounded w-full p-1 text-black" required></textarea>
                            </div>
                            <div class="mt-4">
                                <label for="date" class="block text-black text-sm">Data del esdeveniment:</label>
                                <input type="date" id="date" v-model="event.date"
                                    class="border rounded w-full p-2 text-black" required />
                            </div>
                            <div class="flex space-x-2 mt-4">
                                <div class="flex-1">
                                    <label for="longitude" class="block text-black text-sm">Longitud:</label>
                                    <input type="number" step="any" id="longitude" v-model="event.longitude"
                                        class="border rounded w-full p-1 text-black" required />
                                </div>
                                <div class="flex-1">
                                    <label for="latitude" class="block text-black text-sm">Latitud:</label>
                                    <input type="number" step="any" id="latitude" v-model="event.latitude"
                                        class="border rounded w-full p-1 text-black" required />
                                </div>
                            </div>
                            <div class="mt-4">
                                <label for="time" class="block text-black text-sm">Hora del esdeveniment:</label>
                                <input type="time" id="time" v-model="event.time"
                                    class="border rounded w-full p-1 text-black" required />
                            </div>
                            <div class="mt-4">
                                <label for="image" class="block text-black">Imatge:</label>
                                <input type="file" id="image" ref="imageInput" @change="handleFileUpload"
                                    class="border rounded w-full p-2 text-black" accept="image/*" />

                            </div>
                            <div class="mt-4">
                                <button type="submit"
                                    class="bg-SubastaButton1 text-white px-4 py-2 rounded">Crear</button>
                                <button type="button" @click="closeModalE"
                                    class="bg-red-500 text-white px-4 py-2 rounded ml-4">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal add product -->
                <div v-if="isModalProductOpen" class="fixed inset-0 flex items-center justify-center z-50">
                    <div class="bg-white p-4 rounded-lg shadow-lg w-1/2">
                        <h2 class="text-lg font-semibold mb-2">Crear Producte</h2>
                        <form @submit.prevent="addProduct" enctype="multipart/form-data">
                            <div>
                                <label for="name" class="block text-black text-sm">Nom del Producte:</label>
                                <input type="text" id="name" v-model="product.name"
                                    class="border rounded w-full p-1 text-black" required />
                            </div>
                            <div>
                                <label for="description" class="block text-black text-sm">Descripció del
                                    Producte:</label>
                                <textarea id="description" v-model="product.description"
                                    class="border rounded w-full p-1 text-black" required></textarea>
                            </div>
                            <div class="mt-4">
                                <label for="price" class="block text-black text-sm">Preu del Producte:</label>
                                <input type="number" id="price" v-model="product.price"
                                    class="border rounded w-full p-2 text-black" required />
                            </div>
                            <div class="flex space-x-2 mt-4">
                                <div class="flex-1">
                                    <label for="longitude" class="block text-black text-sm">Longitud:</label>
                                    <input type="number" step="any" id="longitude" v-model="product.longitude"
                                        class="border rounded w-full p-1 text-black" required />
                                </div>
                                <div class="flex-1">
                                    <label for="latitude" class="block text-black text-sm">Latitud:</label>
                                    <input type="number" step="any" id="latitude" v-model="product.latitude"
                                        class="border rounded w-full p-1 text-black" required />
                                </div>
                            </div>
                            <div class="mt-4">
                                <label for="status" class="block text-black text-sm">Estat:</label>
                                <select id="status" v-model="product.status"
                                    class="border rounded w-full p-2 text-black" required>
                                    <option value="active">Actiu</option>
                                    <option value="inactive">Inactiu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="category" class="block text-black text-sm">Categoria:</label>
                                <div class="relative">
                                    <select id="category_id" v-model="product.category_id"
                                        class="form-input pl-10 text-black" required>
                                        <option value="1">Llar</option>
                                        <option value="2">Tecnología</option>
                                        <option value="3">Esports</option>
                                        <option value="4">Moda</option>
                                        <option value="5">Salut i Bellesa</option>
                                        <option value="6">Joguines</option>
                                    </select>
                                    <span class="form-icon">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label for="image" class="block text-black">Imatge:</label>
                                <input type="file" id="image" @change="handleProductImageUpload"
                                    class="border rounded w-full p-2 text-black" accept="image/*" required />
                            </div>
                            <div class="mt-4">
                                <button type="submit"
                                    class="bg-SubastaButton1 text-white px-4 py-2 rounded">Crear</button>
                                <button type="button" @click="closeModalP"
                                    class="bg-red-500 text-white px-4 py-2 rounded ml-4">Cancelar</button>
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
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Toast Message -->
            <div v-if="showToast" class="fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg transition-all duration-500"
                :class="toastType === 'success' ? 'bg-green-500' : 'bg-red-500'">
                <p class="text-white">{{ toastMessage }}</p>
            </div>
        </div>
    </component>
</template>