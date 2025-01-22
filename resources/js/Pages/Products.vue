<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Barra de navegación con efecto glass -->
    <nav class="glass-effect fixed w-full z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
            <Link href="/" class="flex items-center space-x-2">
              <img src="/uploads/img/logo.png" alt="SwapSell Logo" class="h-8 w-8 rounded-full">
              <span class="text-2xl font-extrabold bg-gradient-to-r from-custom-blue to-custom-blue-light bg-clip-text text-transparent">
                <span class="text-custom-blue-dark">SwapSell</span>
              </span>
            </Link>
          </div>
          <div class="flex items-center space-x-6">
            <Link href="/favorites" class="text-gray-600 hover:text-custom-blue transition-colors">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
            </Link>
            <Link href="/profile" class="text-gray-600 hover:text-custom-blue transition-colors">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </Link>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section con wave background -->
    <div class="wave-bg pt-24 pb-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-white text-center mb-8">
          Encuentra lo que buscas
        </h1>
        <div class="max-w-3xl mx-auto">
          <div class="relative">
            <input type="text" 
                   placeholder="¿Qué estás buscando?" 
                   class="w-full pl-12 pr-4 py-4 rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-white/50">
            <div class="absolute left-4 top-4">
              <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Categorías con efecto glass -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
      <div class="glass-effect rounded-xl p-4">
        <div class="flex space-x-4 overflow-x-auto py-2">
          <button class="category-active px-4 py-2 rounded-full flex items-center space-x-2">
            <span class="text-lg">⭐</span>
            <span>Todo</span>
          </button>
          <button class="category-inactive px-4 py-2 rounded-full flex items-center space-x-2">
            <span class="text-lg">📱</span>
            <span>Electrónicos</span>
          </button>
          <button class="category-inactive px-4 py-2 rounded-full flex items-center space-x-2">
            <span class="text-lg">🪑</span>
            <span>Muebles</span>
          </button>
          <!-- Más categorías estáticas aquí -->
        </div>
      </div>
    </div>

    <!-- Grid de productos -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Producto de ejemplo -->
        <div class="block">
          <div class="glass-effect rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <img src="https://images.unsplash.com/photo-1591337676887-a217a6970a8a?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                 alt="iPhone 13 Pro" 
                 class="w-full h-48 object-cover">
            <div class="p-4">
              <div class="flex justify-between items-start">
                <h3 class="text-lg font-semibold text-custom-blue">iPhone 13 Pro</h3>
                <span class="text-custom-blue font-bold">S/. 799</span>
              </div>
              <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                <div class="flex items-center">
                  <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  </svg>
                  Lima
                  <span class="mx-2">•</span>
                  <span>Hace 2h</span>
                </div>
                <button class="text-gray-400 hover:text-red-500 transition-colors">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
        <!-- Fin producto de ejemplo -->
      </div>
    </div>

    <!-- Botón flotante de venta -->
    <Link href="/sell" 
          class="fixed bottom-8 right-8 bg-custom-blue text-white rounded-full p-4 shadow-lg transition-all duration-300 transform hover:-translate-y-1 hover:bg-custom-blue-dark">
      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
      </svg>
    </Link>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue';




</script>
