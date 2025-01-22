<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    surname: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="wave-bg min-h-screen">
        <section class="min-h-screen flex items-center justify-center p-4 relative">
            <div class="glass-effect rounded-3xl w-full max-w-md p-8 space-y-8">
                <!-- Logo estático -->
                <div class="text-center space-y-4">
                    <div>
                        <img class="w-24 h-24 mx-auto rounded-full shadow-2xl p-2 bg-white" 
                             src="/images/logo.png"
                             alt="SwapSell Logo"
                             style="width: 170px; height: 150px;">
                    </div>
                    <p class="text-gray-400">Crea el teu compte</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Input Nombre -->
                    <div class="form-group relative">
                        <input type="text" 
                               v-model="form.name"
                               required
                               class="w-full px-4 py-3 pl-10 rounded-xl border-2 border-gray-200 
                                      bg-white/5 backdrop-blur-sm text-gray-700 
                                      focus:border-custom-blue focus:ring-2 focus:ring-custom-blue/20 
                                      outline-none transition-all"
                               placeholder="Nom">
                        <div class="absolute left-3 top-3.5">
                            <svg class="h-5 w-5 text-custom-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <!-- Input Apellido -->
                    <div class="form-group relative">
                        <input type="text" 
                               v-model="form.surname"
                               required
                               class="w-full px-4 py-3 pl-10 rounded-xl border-2 border-gray-200 
                                      bg-white/5 backdrop-blur-sm text-gray-700 
                                      focus:border-custom-blue focus:ring-2 focus:ring-custom-blue/20 
                                      outline-none transition-all"
                               placeholder="Cognoms">
                        <div class="absolute left-3 top-3.5">
                            <svg class="h-5 w-5 text-custom-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div v-if="form.errors.surname" class="text-red-500 text-sm mt-1">
                            {{ form.errors.surname }}
                        </div>
                    </div>

                    <!-- Input Email -->
                    <div class="form-group relative">
                        <input type="email" 
                               v-model="form.email"
                               required
                               class="w-full px-4 py-3 pl-10 rounded-xl border-2 border-gray-200 
                                      bg-white/5 backdrop-blur-sm text-gray-700 
                                      focus:border-custom-blue focus:ring-2 focus:ring-custom-blue/20 
                                      outline-none transition-all"
                               placeholder="Correu electrònic">
                        <div class="absolute left-3 top-3.5">
                            <svg class="h-5 w-5 text-custom-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Input Contraseña -->
                    <div class="form-group relative">
                        <input type="password"
                               v-model="form.password"
                               required
                               class="w-full px-4 py-3 pl-10 rounded-xl border-2 border-gray-200 
                                      bg-white/5 backdrop-blur-sm text-gray-700 
                                      focus:border-custom-blue focus:ring-2 focus:ring-custom-blue/20 
                                      outline-none transition-all"
                               placeholder="Contrasenya">
                        <div class="absolute left-3 top-3.5">
                            <svg class="h-5 w-5 text-custom-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- Input Confirmar Contraseña -->
                    <div class="form-group relative">
                        <input type="password"
                               v-model="form.password_confirmation"
                               required
                               class="w-full px-4 py-3 pl-10 rounded-xl border-2 border-gray-200 
                                      bg-white/5 backdrop-blur-sm text-gray-700 
                                      focus:border-custom-blue focus:ring-2 focus:ring-custom-blue/20 
                                      outline-none transition-all"
                               placeholder="Confirma la contrasenya">
                        <div class="absolute left-3 top-3.5">
                            <svg class="h-5 w-5 text-custom-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Botón de registro -->
                    <button type="submit" 
                            :disabled="form.processing"
                            class="w-full py-3 px-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl
                                   transform hover:-translate-y-1 hover:shadow-lg
                                   active:scale-95 transition-all duration-300
                                   relative overflow-hidden group
                                   disabled:opacity-50">
                        <span class="relative z-10">
                            {{ form.processing ? 'Registrant...' : 'Registrar-se' }}
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
                    </button>

                    <!-- Link a Login -->
                    <div class="text-center">
                        <Link :href="route('login')" 
                              class="text-sm text-custom-blue hover:text-opacity-80 transition-colors">
                            Ja tens un compte? Inicia sessió
                        </Link>
                    </div>
                </form>
            </div>
        </section>
    </div>
</template>

<style scoped>
/* Colores personalizados */
.bg-custom-blue {
    background-color: #004266;
}
.text-custom-blue {
    color: #004266;
}

/* Efectos */
.glass-effect {
    background: rgb(255, 255, 255);
    box-shadow: 0 8px 32px 0 rgba(0, 66, 102, 0.2);
}

/* Fondos y animaciones */
.wave-bg {
    background: linear-gradient(45deg, #004266, #006699);
    background-size: 400% 400%;
    animation: gradient 15s ease infinite;
}

@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
</style>
