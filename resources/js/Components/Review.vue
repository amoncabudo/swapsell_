<script setup>
import { ref } from 'vue';
import axios from 'axios';

// Definir los datos del formulario
const formData = ref({
  rating: 0,
  comment: '',
});

// Definir la visibilidad del modal
const isModalOpen = ref(false);

// Definir la puntuación seleccionada
const rating = ref(0);

// Función para abrir el modal
const openModal = () => {
  isModalOpen.value = true;
};

// Función para cerrar el modal
const closeModal = () => {
  isModalOpen.value = false;
};

// Función para establecer la puntuación
const setRating = (value) => {
  rating.value = value;
};

// Función para manejar el envío del formulario
const submitForm = async (event) => {
  event.preventDefault(); // Prevenir que el formulario se envíe de forma tradicional

  formData.value.rating = rating.value; // Asignar la puntuación seleccionada

  try {
    const response = await axios.post('/reviewAdd', formData.value); // Cambia esta URL por la de tu API
    console.log('Formulario enviado con éxito');
    closeModal(); // Cerrar el modal tras el envío exitoso
  } catch (error) {
    console.error('Error al enviar el formulario', error);
  }
};
</script>

<template>
  <!-- Modal toggle -->
  <button
    @click="openModal"
    class="block text-white bg-green-500 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
    type="button"
  >
    Review
  </button>

  <!-- Main modal -->
  <div
    v-if="isModalOpen"
    tabindex="-1"
    aria-hidden="true"
    class="fixed inset-0 bg-gray-500 bg-opacity-50 z-50 flex justify-center items-center"
  >
    <div class="relative p-4 w-full max-w-md max-h-full bg-white rounded-lg shadow-sm dark:bg-gray-700">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div
          class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200"
        >
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
            Valoració
          </h3>
          <button
            type="button"
            @click="closeModal"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          >
            <svg
              class="w-3 h-3"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 14 14"
            >
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
              />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>

        <!-- Modal body -->
        <div class="p-4 md:p-5">
          <form @submit="submitForm" class="space-y-4">
            

            

            <!-- Estrellas -->
            <div>
              <label class="flex justify-center items-center block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valora la teva experiència</label>
              <div class="flex items-center justify-center space-x-1">
                <svg
                  v-for="n in 5"
                  :key="n"
                  @click="setRating(n)"
                  :class="{'text-yellow-300': n <= rating, 'text-gray-300': n > rating}"
                  class="w-10 h-10 cursor-pointer"
                  aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  viewBox="0 0 22 20"
                >
                  <path
                    d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"
                  />
                </svg>
              </div>
            </div>

            <!-- Mostrar la puntuación seleccionada -->
            <div class="mt-2 text-center text-sm text-gray-600">
              Puntuació seleccionada: {{ rating }}
            </div>

            <div>
              <input
                v-model="formData.comment"
                type="text"
                class="w-full border-gray-300 rounded-lg focus:ring-4 focus:outline-none focus:ring-blue-300 text-sm px-5 py-2.5 dark:bg-gray-800 dark:border-gray-700"
                placeholder="Escriu un comentari"
              />
            </div>

            <button
              type="submit"
              class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >
              Valorar
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
