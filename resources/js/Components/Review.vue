<script setup>
import { ref } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
  userId: Number,
  hasReview: {
    type: Boolean,
    default: false
  }
});

const formData = ref({
  rating: 0,
  comment: '',
  user_id: props.userId
});

const isModalOpen = ref(false);
const rating = ref(0);
const isSubmitting = ref(false);

const openModal = () => {
  isModalOpen.value = true;
  document.body.style.overflow = 'hidden';
};

const closeModal = () => {
  isModalOpen.value = false;
  document.body.style.overflow = 'auto';
};

const setRating = (value) => {
  rating.value = value;
  formData.value.rating = value;
};

const submitForm = async (event) => {
  event.preventDefault();
  if (isSubmitting.value) return;
  
  isSubmitting.value = true;
  
  try {
    await axios.post('/reviewAdd', formData.value);
    
    Swal.fire({
      icon: 'success',
      title: '¡Gràcies!',
      text: 'La teva valoració ha estat enviada correctament',
      confirmButtonColor: '#004265',
      showConfirmButton: false,
      timer: 2000,
      timerProgressBar: true,
      backdrop: `
        rgba(0,66,101,0.4)
        url("/images/confetti.gif")
        center top
        no-repeat
      `
    });
    
    closeModal();
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'No s\'ha pogut enviar la valoració',
      confirmButtonColor: '#004265'
    });
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<template>
  <button
    v-if="!props.hasReview"
    @click="openModal"
    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#004265] to-[#005285] text-white rounded-lg 
           hover:from-[#003255] hover:to-[#004275] transition-all duration-300 transform hover:scale-105 
           hover:shadow-lg hover:shadow-[#004265]/25 focus:outline-none focus:ring-2 focus:ring-[#004265] 
           focus:ring-offset-2 space-x-2 w-full justify-center"
  >
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
    </svg>
    <span>Valorar</span>
  </button>

  <Transition
    enter-active-class="transition duration-300 ease-out"
    enter-from-class="transform scale-95 opacity-0"
    enter-to-class="transform scale-100 opacity-100"
    leave-active-class="transition duration-200 ease-in"
    leave-from-class="transform scale-100 opacity-100"
    leave-to-class="transform scale-95 opacity-0"
  >
    <div v-if="isModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closeModal"></div>

        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

        <div class="relative inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
          <div class="bg-[#004265] px-4 py-3 sm:px-6">
            <h3 class="text-2xl font-bold leading-6 text-white mb-1" id="modal-title">
              La teva opinió és important
            </h3>
          </div>

          <div class="bg-white px-4 py-5 sm:p-6">
            <form @submit="submitForm" class="space-y-6">
              <div class="space-y-4">
                <label class="block text-center text-lg font-medium text-gray-900">
                  Com valoraries la teva experiència?
                </label>
                <div class="flex items-center justify-center space-x-3">
                  <template v-for="n in 5" :key="n">
                    <button
                      type="button"
                      @click="setRating(n)"
                      @mouseenter="rating = n"
                      @mouseleave="rating = formData.rating"
                      class="focus:outline-none transition-transform duration-200 hover:scale-125"
                      :class="{'scale-110': n <= rating}"
                    >
                      <svg class="w-12 h-12" :class="{'text-yellow-400': n <= rating, 'text-gray-300': n > rating}"
                           xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                      </svg>
                    </button>
                  </template>
                </div>
              </div>

              <div class="space-y-2">
                <textarea
                  v-model="formData.comment"
                  rows="4"
                  class="w-full p-4 bg-white rounded-xl border-2 border-[#004265]/30 
                         focus:border-[#004265] focus:ring-2 focus:ring-[#004265] focus:ring-opacity-50 
                         transition-all duration-200 resize-none placeholder-gray-400 text-gray-900"
                  placeholder="Comparteix la teva experiència..."
                ></textarea>
              </div>

              <div class="flex justify-end space-x-3 pt-4">
                <button
                  type="button"
                  @click="closeModal"
                  class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 
                         transition-all duration-200 focus:outline-none focus:ring-2 
                         focus:ring-[#004265] focus:ring-offset-2"
                >
                  Cancel·lar
                </button>
                <button
                  type="submit"
                  :disabled="isSubmitting"
                  class="px-6 py-3 bg-[#004265] text-white rounded-xl hover:bg-[#003255]
                         transition-all duration-200 focus:outline-none focus:ring-2 
                         focus:ring-[#004265] focus:ring-offset-2 disabled:opacity-50 
                         disabled:cursor-not-allowed flex items-center space-x-2"
                >
                  <span>Enviar valoració</span>
                  <svg v-if="isSubmitting" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: .5;
  }
}

/* Sombras personalizadas */
.shadow-custom {
  box-shadow: 0 4px 6px -1px rgba(0, 66, 101, 0.1), 0 2px 4px -1px rgba(0, 66, 101, 0.06);
}

.hover\:shadow-custom:hover {
  box-shadow: 0 10px 15px -3px rgba(0, 66, 101, 0.1), 0 4px 6px -2px rgba(0, 66, 101, 0.05);
}
</style>
