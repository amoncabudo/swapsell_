<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    name: Object,
});

const form = useForm({
    title: '',
    description: '',
    price: '',
    category: '',
    condition: '',
    images: null,
});

const submit = () => {
    form.post(route('product.store'));
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Vender Producto" />
        <div class="container mx-auto p-4">
            <h1 class="text-3xl font-bold mb-6">
                Hola {{ name.name }} - Publicar Producto
            </h1>

            <form @submit.prevent="submit" class="max-w-2xl bg-white p-6 rounded-lg shadow-lg">
                <!-- Título del producto -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                        Título del producto
                    </label>
                    <input 
                        v-model="form.title"
                        type="text" 
                        id="title"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Ingresa el título de tu producto"
                    >
                    <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">
                        {{ form.errors.title }}
                    </div>
                </div>

                <!-- Descripción -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Descripción
                    </label>
                    <textarea 
                        v-model="form.description"
                        id="description"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        rows="4"
                        placeholder="Describe tu producto"
                    ></textarea>
                </div>

                <!-- Precio -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                        Precio
                    </label>
                    <input 
                        v-model="form.price"
                        type="number" 
                        id="price"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="0.00"
                        step="0.01"
                    >
                </div>

                <!-- Categoría -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                        Categoría
                    </label>
                    <select 
                        v-model="form.category"
                        id="category"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    >
                        <option value="">Selecciona una categoría</option>
                        <option value="electronics">Electrónica</option>
                        <option value="clothing">Ropa</option>
                        <option value="home">Hogar</option>
                        <option value="other">Otros</option>
                    </select>
                </div>

                <!-- Condición -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="condition">
                        Estado del producto
                    </label>
                    <select 
                        v-model="form.condition"
                        id="condition"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    >
                        <option value="">Selecciona el estado</option>
                        <option value="new">Nuevo</option>
                        <option value="like_new">Como nuevo</option>
                        <option value="good">Buen estado</option>
                        <option value="fair">Estado regular</option>
                    </select>
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-between mt-6">
                    <button 
                        type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Publicando...' : 'Publicar Producto' }}
                    </button>
                    <Link 
                        :href="route('dashboard')" 
                        class="text-blue-500 hover:text-blue-800"
                    >
                        Cancelar
                    </Link>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>