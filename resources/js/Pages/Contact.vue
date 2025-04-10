<script setup>
import Footer from '../Components/Footer.vue';
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import Cookies from "@/Components/Cookies.vue";
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

const items = ref([
    { label: 'Inici', url: '/' },
    { label: 'Contacte' }
]);
const showToast = ref(false);
const toastMessage = ref('');
const toastType = ref('success');

const form = useForm({
    name: '',
    cognom: '',
    email: '',
    subject: '',
    message: ''
});

const submitForm = () => {
    form.post(route('contact.send'), {
        onSuccess: () => {
            console.log('Formulario enviado con éxito');
            toastMessage.value = 'Formulari enviat amb èxit!';
            toastType.value = 'success';
            showToast.value = true;
            form.reset();
            setTimeout(() => {
                showToast.value = false;
            }, 5000);
        },
        onError: (error) => {
            console.error('Error al enviar el formulario:', error);
            toastMessage.value = 'Error al enviar el formulari';
            toastType.value = 'error';
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 5000);
        }
    });
};
</script>

<template>

    <Head title="Contacte"></Head>
    <div class="min-h-screen bg-gray-50">
        <main class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <Breadcrumb :model="items" class="mb-8" />

                <!-- Information panel -->
                <div class="bg-white rounded-xl shadow-lg p-8 mb-16" data-aos="fade-up">
                    <h1 class="text-4xl font-bold text-gray-800 mb-8" data-aos="fade-right" data-aos-delay="100">
                        Contacta amb Nosaltres
                    </h1>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8" data-aos="fade-up" data-aos-delay="300">
                        <div class="contact-card" data-aos="zoom-in" data-aos-delay="400">
                            <div class="icon-wrapper">
                                <i class="pi pi-envelope text-2xl"></i>
                            </div>
                            <h2 class="text-xl font-semibold mb-2 text-gray-800">Email</h2>
                            <p class="text-gray-800">info@swapsell.cat</p>
                        </div>

                        <div class="contact-card" data-aos="zoom-in" data-aos-delay="500">
                            <div class="icon-wrapper">
                                <i class="pi pi-phone text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 text-gray-800">Telèfon</h3>
                            <p class="text-gray-800">+34 972 123 456</p>
                        </div>

                        <div class="contact-card" data-aos="zoom-in" data-aos-delay="600">
                            <div class="icon-wrapper">
                                <i class="pi pi-map-marker text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 text-gray-800">Ubicació</h3>
                            <p class="text-gray-800">C/Pelai Martinez 1, Figueres, Girona</p>
                        </div>
                    </div>
                </div>

                <!-- Contact form -->
                <div class="bg-white rounded-xl shadow-lg p-8 text-black" data-aos="fade-up" data-aos-delay="700">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6" data-aos="fade-right" data-aos-delay="800">
                        Envia'ns un Missatge
                    </h2>

                    <form @submit.prevent="submitForm" class="space-y-6" data-aos="fade-up" data-aos-delay="900">
                        <div class="form-group" data-aos="fade-right" data-aos-delay="1000">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                            <input type="text" id="name" v-model="form.name" class="form-input" required>
                        </div>

                        <div class="form-group" data-aos="fade-right" data-aos-delay="1000">
                            <label for="cognom" class="block text-sm font-medium text-gray-700 mb-2">Cognom</label>
                            <input type="text" id="cognom" v-model="form.cognom" class="form-input" required>
                        </div>

                        <div class="form-group" data-aos="fade-right" data-aos-delay="1100">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" v-model="form.email" class="form-input" required>
                        </div>

                        <div class="form-group" data-aos="fade-right" data-aos-delay="1200">
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Assumpte</label>
                            <input type="text" id="subject" v-model="form.subject" class="form-input" required>
                        </div>

                        <div class="form-group" data-aos="fade-right" data-aos-delay="1300">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Missatge</label>
                            <textarea id="message" v-model="form.message" rows="5" class="form-input"
                                required></textarea>
                        </div>

                        <div class="flex justify-end" data-aos="fade-up" data-aos-delay="1300">
                            <button type="submit" class="submit-button btn-send">
                                Enviar Missatge
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <div v-if="showToast"
                        class="fixed bottom-8 right-8 px-8 py-4 rounded-xl shadow-xl transition-all duration-500 transform"
                        :class="toastType === 'success' ? 'bg-green-500' : 'bg-red-500'">
                        <p class="text-white font-medium text-lg">{{ toastMessage }}</p>
                    </div>
        <Cookies />
        <Footer />

    </div>
</template>

<style scoped>
.contact-card {
    @apply bg-white p-6 rounded-lg text-center transition-all duration-300 hover:shadow-lg;
}

.icon-wrapper {
    @apply w-16 h-16 mx-auto mb-4 rounded-full bg-blue-50 flex items-center justify-center text-blue-600;
}

.form-group {
    @apply transition-all duration-300;
}

.form-input {
    @apply w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 outline-none;
}

.form-input:hover {
    @apply border-blue-300;
}

.submit-button {
    @apply bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 hover:bg-blue-700 hover:shadow-lg transform hover:-translate-y-1;
}

.contact-card:hover {
    transform: translateY(-5px);
}

.contact-card:hover .icon-wrapper {
    @apply bg-blue-100;
}

.form-group:focus-within {
    transform: translateX(10px);
}

:deep(.p-breadcrumb) {
    @apply bg-transparent;
}

:deep(.p-breadcrumb-item-label) {
    @apply text-blue-600;
}

:deep(.p-breadcrumb-item-label:hover) {
    @apply text-blue-800;
}

:deep(.p-breadcrumb) {
    background-color: transparent;
}

:deep(.p-breadcrumb-item-label) {
    color: #004266;
}

:deep(.p-breadcrumb-item-label:hover) {
    color: #060606 !important;
}

:deep(.btn-send) {
    background-color: #004266;
}
</style>
