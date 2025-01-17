<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0 dark:bg-gray-900">
        <div class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg dark:bg-gray-800">

        <div>
            <Link href="/">
                <img class="w-24 h-24 mx-auto rounded-full shadow-2xl p-2 bg-white width=" 
                 src="/images/logo.png"
                 alt="SwapSell Logo"
                 style="width: 170px; height: 150px;">
            </Link>
        </div>

        
            <slot />
        </div>
        </div>
    
</template>