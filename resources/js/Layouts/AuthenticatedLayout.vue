<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

</script>

<template>
    
    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <nav class="w-full z-50 bg-white shadow-md dark:bg-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <!-- Logo Section -->
                        <div class="flex items-center">
                            <Link href="/" class="flex items-center space-x-2">
                                <img src="/images/logo.png" alt="SwapSell Logo" class="h-8 w-10 rounded-full">
                                <span class="text-2xl font-extrabold bg-gradient-to-r from-blue-500 to-indigo-400 bg-clip-text text-transparent">
                                    <span class="text-blue-700">SwapSell</span>
                                </span>
                            </Link>
                        </div>
                        

                        <!-- Links Section --->
                        <Link href="/" class="text-gray-600 hover:text-blue-500 transition-colors">
                            Inici
                        </Link>
                        <!-- Productes -->
                        <Link href="/products" class="text-gray-600 hover:text-blue-500 transition-colors">
                            Productes
                        </Link>
                        <!-- Events -->
                        <Link href="/events" class="text-gray-600 hover:text-blue-500 transition-colors">
                            Esdeveniments
                        </Link>
                        <Link href="/auction" class="text-gray-600 hover:text-blue-500 transition-colors">
                            Subhastes
                        </Link>

                        <div class="hidden sm:flex items-center space-x-6">
                            <Link aria-label="Cart Page" href="/Cart" class="text-gray-600 hover:text-blue-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.4 12.8a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 1.9-1.4L23 6H6"></path>
                                </svg>
                            </Link>
                            <Link aria-label="Favorites Page"  href="/favorites" class="text-gray-600 hover:text-blue-500 transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </Link>
                            <Link aria-label="Profile Page"  href="/profile" class="text-gray-600 hover:text-blue-500 transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </Link>

                            <!-- Settings Dropdown -->
                            <div class="relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-800 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                            <template #content>  
                                <DropdownLink :href="route('profile')">Perfil</DropdownLink>
                                <DropdownLink :href="route('Events')">Esdeveniments</DropdownLink>
                                <DropdownLink :href="route('Products')">Productes</DropdownLink>
                                <DropdownLink :href="route('products_favs')">Favorits</DropdownLink>
                                <DropdownLink :href="route('cart')">Carret</DropdownLink>
                                <DropdownLink :href="route('auction')">Subhastes</DropdownLink>
                                <DropdownLink :href="route('reviews.index')">Historial</DropdownLink>
                                <template v-if="$page.props.auth.user.role === 0">
                                    <DropdownLink :href="route('AdminPanel')">Panell d'Administració</DropdownLink>
                                </template>
                                <DropdownLink :href="route('logout')" method="post" as="button">Tancar Sessió</DropdownLink>
                            </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button aria-label="Favorites Page"  @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600">
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">Perfil</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('Events')">Events</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('Products')">Productes</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('products_favs')">Favorits</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('cart')">Carret</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('auction')">Subhastes</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('reviews.index')">Historial</ResponsiveNavLink>
                            <template v-if="$page.props.auth.user.role === 0">
                                <ResponsiveNavLink :href="route('AdminPanel')">Admin Panel</ResponsiveNavLink>
                            </template>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">Tencar sessió</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow dark:bg-gray-800" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
