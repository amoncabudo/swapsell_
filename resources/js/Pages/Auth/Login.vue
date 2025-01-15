<template>
  <div class="wave-bg min-h-screen">
    <section class="min-h-screen flex items-center justify-center p-4 relative">
      <div class="glass-effect rounded-3xl w-full max-w-md p-8 space-y-8">
        <!-- Logo estàtic -->
        <div class="text-center space-y-4">
          <div>
            <img class="w-24 h-24 mx-auto rounded-full shadow-2xl p-2 bg-white" 
                 src="/uploads/img/logo.png"
                 alt="SwapSell Logo">
          </div>
          <p class="text-gray-400">Accedeix al teu compte</p>
        </div>

        <form class="space-y-6">
          <!-- Input Correu -->
          <div class="form-group relative">
            <input type="email" 
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
          </div>

          <!-- Input Contrasenya -->
          <div class="form-group relative">
            <input type="password"
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
          </div>

          <!-- Opcions d'inici de sessió -->
          <div class="flex items-center justify-between">
            <label class="flex items-center space-x-2 cursor-pointer">
              <input type="checkbox" class="w-4 h-4 rounded border-2 border-gray-300 focus:ring-custom-blue text-custom-blue transition-colors">
              <span class="text-sm text-gray-600">Recorda'm</span>
            </label>
            <a href="#" class="text-sm text-custom-blue hover:text-opacity-80 transition-colors">
              Has oblidat la contrasenya?
            </a>
          </div>

          <!-- Botó d'inici de sessió -->
          <button type="submit" 
                  class="w-full py-3 px-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl
                         transform hover:-translate-y-1 hover:shadow-lg
                         active:scale-95 transition-all duration-300
                         relative overflow-hidden group">
            <span class="relative z-10">Iniciar Sessió</span>
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
          </button>
        </form>
      </div>
    </section>
  </div>
</template>
