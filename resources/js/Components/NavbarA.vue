<script setup>
</script>

<template>
        <nav class="fixed w-full z-50 bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo Section -->
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center space-x-2">
                            <img src="/images/logo.png" alt="SwapSell Logo" class="h-8 w-8 rounded-full">
                            <span
                                class="text-2xl font-extrabold bg-gradient-to-r from-blue-500 to-indigo-400 bg-clip-text text-transparent">
                                <span class="text-blue-700">SwapSell</span>
                            </span>
                        </Link>
                    </div>
                    
                    <!-- Links Section -->
                    <div class="flex items-center space-x-6">
                        <Link href="/favorites" class="text-gray-600 hover:text-blue-500 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </Link>
                        <Link href="/profile" class="text-gray-600 hover:text-blue-500 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>
        </nav>
</template>

