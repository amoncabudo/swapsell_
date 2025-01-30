<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

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
  <div class="wave-bg min-h-screen">
    <section class="min-h-screen flex items-center justify-center p-4 relative">
      <div class="glass-effect rounded-3xl w-full max-w-md p-8 space-y-8">
        <!-- Logo estàtic -->
        <div class="text-center space-y-4">
          <div>
            <img class="w-24 h-24 mx-auto rounded-full shadow-2xl p-2 bg-white width=" 
                 src="/images/logo.png"
                 alt="SwapSell Logo"
                 style="width: 170px; height: 150px;">
          </div>
          <p class="text-gray-400">Accedeix al teu compte</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
          <!-- Input Correu -->
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
            <!-- Mensaje de error -->
            <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
              {{ form.errors.email }}
            </div>
          </div>

          <!-- Input Contrasenya -->
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
                     class="w-4 h-4 rounded border-2 border-gray-300 focus:ring-custom-blue text-custom-blue transition-colors">
              <span class="text-sm text-gray-600">Recorda'm</span>
            </label>
            <Link :href="route('password.request')" 
                  class="text-sm text-custom-blue hover:text-opacity-80 transition-colors">
              Has oblidat la contrasenya?
            </Link>
          </div>

          <!-- Botó d'inici de sessió -->
          <button type="submit" 
                  :disabled="form.processing"
                  class="w-full py-3 px-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl
                         transform hover:-translate-y-1 hover:shadow-lg
                         active:scale-95 transition-all duration-300
                         relative overflow-hidden group
                         disabled:opacity-50">
            <span class="relative z-10">
              {{ form.processing ? 'Iniciant sessió...' : 'Iniciar Sessió' }}
            </span>
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
          </button>
        </form>
        <div class="text-center">
        <Link href="/register" class="text-gray-600 hover:text-blue-500 transition-colors">
          <p class="text-custom-blue mt-5 text-sm/6">No tens compte? Registra't</p>
        </Link>
      </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
/* Colores personalizados */
.bg-custom-blue {
    background-color: #004266;
}
.bg-custom-blue-dark {
    background-color: #003252;
}
.bg-custom-blue-light {
    background-color: #006699;
}
.text-custom-blue {
    color: #004266;
}
.text-custom-blue-dark {
    color: #003252;
}
.border-custom-blue {
    border-color: #004266;
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


/* Animaciones */
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-2px); }
}
.animate-float {
    animation: float 3s ease-in-out infinite;
}

/* Estilos para el login */
.logo-container {
    position: relative;
    z-index: 1;
}

.logo-container::before {
    content: '';
    position: absolute;
    inset: -5px;
    background: linear-gradient(45deg, #004266, #006699);
    border-radius: 50%;
    z-index: -1;
    filter: blur(15px);
    opacity: 0.5;
}

/* Inputs personalizados */
.form-group input {
    backdrop-filter: blur(8px);
}

/* Botones */
.btn-gradient {
    background: linear-gradient(45deg, #004266, #006699);
    transition: all 0.3s ease;
}
.btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 66, 102, 0.3);
}

/* Efectos hover */
.hover-scale {
    transition: transform 0.3s ease;
}
.hover-scale:hover {
    transform: scale(1.05);
}

</style>