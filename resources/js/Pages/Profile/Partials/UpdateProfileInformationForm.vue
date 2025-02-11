<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { ref } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    image: user.image,
});

const imagePreview = ref(user.image);
const handleSubmit = () => {
    form.post(route('profile.update'), {
        forceFormData: true, // Necesario para enviar archivos
    });
};

const handleImageChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;

        // Crear una vista previa de la imagen seleccionada
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Informació del Perfil
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Configura la teva informació de perfil
            </p>
        </header>

        <form
           @submit.prevent="handleSubmit"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="name" value="Nom" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full text-black"
                    v-model="form.name"
                  
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Correu Electrònic" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full text-black"
                    v-model="form.email"
                   
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                    El teu correu electrònic no esta verificat.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    >
                        Prem aqui per re-enviar el correu
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                >
                    Un nou enllaç de verificació ha estat enviat al teu correu electrònic.
                </div>
            </div>

            <div>
                <InputLabel for="image" value="Imatge de Perfil" />

                <!-- Usar un elemento input nativo para manejar la carga del archivo -->
                <input
                    id="image"
                    type="file"
                    @change="handleImageChange"
                    accept="image/*"
                    class="mt-1 block w-full text-black"
                />

                <InputError class="mt-2" :message="form.errors.image" />
            </div>

            <!-- Vista previa de la imagen -->
            <div v-if="imagePreview" class="mt-4">
                <img :src="imagePreview" alt="Vista previa de la imagen" class="w-32 h-32 rounded-full object-cover">
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Guardar</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        Guardat.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
