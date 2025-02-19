<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};



</script>

<template>
  <Head title = "Iniciar sessio"></Head>
  <div class="min-h-screen bg-gradient-to-br from-[#004266] to-[#006699] dark:from-[#1b2e37] dark:to-[#224466] animate-gradient">
    <Link aria-label="Volver" href="/"
    class="bg-white rounded-full w-12 h-12 shadow-md transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center absolute top-4 left-4 z-10">
  <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" role="img" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
  </svg>
</Link>

    <section class="min-h-screen flex items-center justify-center p-4 relative">
      <div class="bg-white/90 dark:bg-gray-800 backdrop-blur-md rounded-3xl w-full max-w-md p-8 space-y-8 shadow-2xl">
        <!-- Logo estàtic -->
        <div class="text-center space-y-4">
          <div>
            <img class="w-40 h-36 mx-auto rounded-full shadow-2xl p-2 bg-white dark:bg-gray-900"
                 src="/images/logo.png"
                 alt="SwapSell Logo">
          </div>
          <p class="text-gray-600 dark:text-white">Accedeix al teu compte</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
          <!-- Input Correu -->
          <div class="relative">
            <input type="email" 
                   v-model="form.email"
                   required
                   class="w-full px-4 py-3 pl-10 rounded-xl border-2 border-gray-200 dark:border-gray-800
                          bg-white/50 backdrop-blur-sm text-gray-700 dark:text-white
                          focus:border-[#004266] focus:ring-2 focus:ring-[#004266]/20 
                          outline-none transition-all dark:placeholder-white"
                   placeholder="Correu electrònic">
            <div class="absolute left-3 top-3.5">
              <svg class="h-5 w-5 text-[#004266] dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
            </div>
            <!-- Mensaje de error -->
            <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
              {{ form.errors.email }}
            </div>
          </div>
          
          <!-- Input Contrasenya -->
          <div class="relative">
            <input type="password"
                   v-model="form.password"
                   required
                   class="w-full px-4 py-3 pl-10 rounded-xl border-2 border-gray-200 dark:border-gray-950 
                          bg-white/50 backdrop-blur-sm text-gray-700 dark:text-white
                          focus:border-[#004266] focus:ring-2 focus:ring-[#004266]/20 
                          outline-none transition-all dark:placeholder-white"
                   placeholder="Contrasenya">
            <div class="absolute left-3 top-3.5">
              <svg class="h-5 w-5 text-[#004266] dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
            </div>
            <!-- Mensaje de error -->
            <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
              {{ form.errors.password }}
            </div>
          </div>

          <!-- Opcions d'inici de sessió -->
          <div class="flex items-center justify-between">
            <label class="flex items-center space-x-2 cursor-pointer">
              <input type="checkbox" 
                     v-model="form.remember"
                     class="w-4 h-4 rounded border-2 border-gray-300 dark:border-gray-950 focus:ring-[#004266] text-[#004266] transition-colors">
              <span class="text-sm text-gray-600 dark:text-white">Recorda'm</span>
            </label>
            <Link :href="route('password.request')" 
                  class="text-sm text-[#004266] dark:text-white hover:text-opacity-80 transition-colors">
              Has oblidat la contrasenya?
            </Link>
          </div>
          
          <!-- Botó d'inici de sessió -->
          <button type="submit" 
                  :disabled="form.processing"
                  class="w-full py-3 px-4 bg-gradient-to-r from-[#004266] to-[#006699] text-white rounded-xl
                         transform hover:-translate-y-1 hover:shadow-lg
                         active:scale-95 transition-all duration-300
                         relative overflow-hidden group
                         disabled:opacity-50">
            <span class="relative z-10">
              {{ form.processing ? 'Iniciant sessió...' : 'Iniciar Sessió' }}
            </span>
            <div class="absolute inset-0 bg-gradient-to-r from-[#006699] to-[#004266] transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
          </button>
        </form>
        <div class="inline-flex items-center justify-center w-full">
          <hr class="w-64 h-px bg-gray-300 border-0">
          <span class="absolute px-3 font-medium text-gray-600 -translate-x-1/2  backdrop-blur-sm left-1/2 dark:text-white">O també</span>
        </div>
        <div class="flex items-center justify-center">
          <a
            :href="route('login-google')"
            class="flex items-center justify-center gap-2 w-full max-w-xs px-4 py-2 text-white bg-red-600 hover:bg-red-700 
                   rounded-lg shadow-md transition duration-300 font-medium"
          >
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google Logo" class="w-5 h-5" />
            Iniciar sessio amb Google
          </a>
        </div>
        <div class="text-center">
          <Link href="/register" class="text-gray-600 hover:text-[#004266] transition-colors">
            <p class="text-[#004266] dark:text-white mt-5 text-sm/6">No tens compte? Registra't</p>
          </Link>
        </div>
      </div>
    </section>
  </div>
</template>