<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { watch, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import Cookies from "@/Components/Cookies.vue";

const props = defineProps({
  isAuthenticated: Boolean,
  userProducts: Array
});

const dateError = ref('');

let form = useForm({
  product_id: null,
  start_price: null,
  current_price: null,
  start_time: null,
  end_time: null,
  status: true
});

watch(() => form.start_price, (newPrice) => {
  form.current_price = newPrice;
});

watch(() => form.end_time, (newEndTime) => {
  if (form.start_time && newEndTime) {
    const startDate = new Date(form.start_time);
    const endDate = new Date(newEndTime);

    if (endDate <= startDate) {
      dateError.value = 'La data de fi ha de ser posterior a la data de inici';
      form.end_time = null;
    } else {
      dateError.value = '';
    }
  }
});
</script>

<template>
  <Head title="Formulari subhaste" />
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-[#1b2e37] dark:to-[#224466] py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
          <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">
            Crear nova subhasta
          </h1>
          <p class="text-gray-600 dark:text-white">
            Selecciona un producte, estableix el preu inicial i les dates de la subhasta.
          </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
          <form @submit.prevent="form.post(route('auctions.store'), {
            onSuccess: () => {
              // Redirect or show success message
            },
            onError: (errors) => {
              console.error(errors);
            }
          })" class="space-y-6">
            <!-- Select the product -->
            <div>
              <label for="product" class="block text-sm font-medium text-gray-700 dark:text-white">Selecciona el Producte</label>
              <div class="relative mt-1">
                <select id="product" v-model="form.product_id" class="dark:bg-gray-950 dark:text-white block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-950 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                  <option class="dark:text-white" value="">Selecciona un producte</option>
                  <option class="dark:text-white" v-for="product in userProducts" :key="product.id" :value="product.id" v-show="product.bid === 0">
                    {{ product.name }} - {{ product.price }}€
                  </option>
                </select>
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                  <svg class="w-5 h-5 text-gray-400 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Initial price -->
            <div>
              <label for="start_price" class="block text-sm font-medium text-gray-700 dark:text-white">Preu Inicial (€)</label>
              <div class="relative mt-1">
                <input type="number" id="start_price" v-model="form.start_price" class="dark:bg-gray-950 dark:text-white block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required min="0" step="0.01" />
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                  <svg class="w-5 h-5 text-gray-400 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Start date and time -->
            <div>
              <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-white">Inici de la Subhasta</label>
              <div class="relative mt-1">
                <input type="datetime-local" id="start_time" v-model="form.start_time" class="dark:bg-gray-950 dark:text-white block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required />
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                  <svg class="w-5 h-5 text-gray-400 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- End date and time -->
            <div>
              <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-white">Fi de la Subhasta</label>
              <div class="relative mt-1">
                <input type="datetime-local" id="end_time" v-model="form.end_time" class="dark:bg-gray-950 dark:text-white block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" :min="form.start_time" required />
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </div>
              <p v-if="dateError" class="mt-1 text-sm text-red-500">{{ dateError }}</p>
            </div>

            <!-- Submit button -->
            <div class="mt-8">
              <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-[#006699] dark:to-[#004266] text-white font-bold py-2 px-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 ease-in-out transform hover:-translate-y-1">
                Crear Subhasta
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </component>
  <Cookies />
</template>

