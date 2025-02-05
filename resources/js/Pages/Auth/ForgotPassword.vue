<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Heu oblidat la contrasenya? No hi ha problema. Només has de fer-nos saber el teu correu
adreça i t'enviarem un correu electrònic amb un enllaç de restabliment de contrasenya que et permetrà
tu per triar-ne un de nou.
        </div>

        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-green-600 dark:text-green-400"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                Enllaç de restabliment de contrasenya per correu
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
