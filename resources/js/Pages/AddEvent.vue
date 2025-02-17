<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { Head } from '@inertiajs/vue3';
import Cookies from "@/Components/Cookies.vue";

const props = defineProps({ //Define props
    isAuthenticated: Boolean
});

let form = useForm({ //Define form
  title: "", //Define title
  description: "", //Define description
  date: null, //Define date
  time: "active", //Define time
  longitude: null, //Define longitude
  latitude: null, //Define latitude
  image: null //Define image
});

function handleFileUpload(event) { //Define handleFileUpload
  form.image = event.target.files[0]; //Define image
}

</script>

<template>
  <Head title="Crea un Esdeveniment"></Head>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-2xl mx-auto">
        <!-- Encabezado del formulario -->
        <div class="text-center mb-8">
          <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
            Crea un Esdeveniment
          </h1>
          <p class="text-gray-600">
            Completa els detalls del teu esdeveniment:
          </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
          <form @submit.prevent="form.post(route('events'))" class="space-y-6">
            <div class="form-group">
              <label for="title" class="form-label">Nom de l'esdeveniment</label>
              <div class="relative">
                <input type="text" id="title" v-model="form.title" class="form-input pl-10" required />
              </div>
            </div>

            <!-- Description camp -->
            <div class="form-group">
              <label for="description" class="form-label">Descripció</label>
              <div class="relative">
                <textarea id="description" v-model="form.description" rows="4" class="form-input" required></textarea>
              </div>
            </div>

            <!-- Date camp -->
            <div class="form-group">
              <label for="date" class="form-label">Data</label>
              <div class="relative">
                <input type="date" id="price" v-model="form.date" class="form-input pl-10" required />
                <span class="form-icon">
                  <svg class="w-[20px] h-[20px] text-gray-800 dark:text-white" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
                  </svg>

                </span>
              </div>
            </div>

            <!-- Time camp -->
            <div class="form-group">
              <label for="time" class="form-label">Hora</label>
              <div class="relative">
                <input type="time" id="time" v-model="form.time" class="form-input pl-10" required />
                <span class="form-icon">
                  <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Longitude camp -->
            <div class="form-group">
                <label for="latitude" class="form-label">Latitud</label>
                <div class="relative">
                  <input type="number" step="any" id="latitude" v-model="form.latitude" class="form-input pl-10"
                    required />
                  <span class="form-icon">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                      xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.8 13.938h-.011a7 7 0 1 0-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155Z" />
                    </svg>
                  </span>
                </div>
              </div>
            <!-- Latitude camp -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="form-group">
                <label for="longitude" class="form-label">Longitud</label>
                <div class="relative">
                  <input type="number" step="any" id="longitude" v-model="form.longitude" class="form-input pl-10"
                    required />
                  <span class="form-icon">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                      xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.8 13.938h-.011a7 7 0 1 0-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155Z" />
                    </svg>
                  </span>
                </div>
              </div>


            </div>
            <!-- Image camp -->
            <div class="form-group">
              <label for="image" class="form-label">Imatge de l'esdeveniment</label>
              <div class="relative">
                <input type="file" id="image" @change="handleFileUpload" required />
              </div>
            </div>
            <!-- Submit button -->
            <div class="mt-8">
              <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold py-3 px-4 rounded-xl
                       hover:from-blue-600 hover:to-indigo-700 transform hover:-translate-y-0.5 transition-all duration-150
                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Publicar Esdeveniment
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </component>
  <Cookies />
</template>

<style scoped>
/* Form styles */
.form-group {
  margin-top: 0.25rem; /*Margin top*/
}

.form-label {
  display: block; /*Display block*/
  font-size: 0.875rem; /*Font size*/
  font-weight: 500; /*Font weight*/
  color: rgb(55, 65, 81); /*Color in grey*/
}

.form-input {
  display: block; /*Display block*/
  width: 100%; /*Width*/
  border-radius: 0.5rem; /*Border radius*/
  border-color: rgb(209, 213, 219); /*Border color in white*/
  box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); /*Box shadow*/
  padding-left: 3rem; /*Padding left*/
  color: rgb(17, 24, 39); /*Color*/
  transition-property: color, background-color, border-color; /*Transition property*/
  transition-duration: 200ms; /*Transition duration*/
}

.form-input:focus {
  border-color: rgb(59, 130, 246); /*Border color in blue*/
  outline: 2px solid rgb(59, 130, 246); /*Outline in blue*/
}

.form-icon {
  position: absolute; /*Position absolute*/
  left: 0.70rem; /*Left*/
  top: 50%; /*Top*/
  transform: translateY(-50%); /*Transform translateY*/
  color: rgb(156, 163, 175); /*Color*/
  pointer-events: none; /*Pointer events*/
  border-right: 1px solid rgb(209, 213, 219); /*Border right*/
  padding-right: 0.75rem; /*Padding right*/
}

/* Styles for textarea */
textarea.form-input {
  padding-left: 3rem; /*Padding left*/
  resize: none; /*Resize none*/
  color: rgb(17, 24, 39); /*Color*/
}

/* Disable arrows in numeric inputs */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Effects and animations */
.wave-bg {
  background: linear-gradient(45deg, #004266, #006699); /*Background in blue*/
  background-size: 400% 400%; /*Background size*/
  animation: gradient 15s ease infinite; /*Animation*/
}

@keyframes gradient {
  0% {
    background-position: 0% 50%; /*Background position*/
  }

  50% {
    background-position: 100% 50%;
  }

  100% {
    background-position: 0% 50%;
  }
}

/* Glass effect */
.glass-effect {
  background: rgba(255, 255, 255, 0.7); /*Background in white*/
  backdrop-filter: blur(10px); /*Backdrop filter*/
  border: 1px solid rgba(255, 255, 255, 0.2); /*Border*/
  box-shadow: 0 8px 32px 0 rgba(0, 66, 102, 0.2); /*Box shadow*/
}

/* Custom colors */
.bg-custom-blue {
  background-color: #004266; /*Background in blue*/
}

.text-custom-blue {
  color: #004266; /*Color in blue*/
}

/* Styles for the submit button */
.submit-button {
  width: 100%; /*Width*/
  background: linear-gradient(to right, #004266, #006699); /*Background in blue*/
  color: white; /*Color in white*/
  font-weight: bold; /*Font weight*/
  padding: 0.75rem 1rem; /*Padding*/
  border-radius: 0.75rem; /*Border radius*/
  transition: background 0.15s ease-in-out; /*Transition*/
}

.submit-button:hover {
  background: linear-gradient(to right, #00334d, #00527a); /*Background in blue*/
  transform: translateY(-0.25rem); /*Transform translateY*/
}

.submit-button:focus {
  outline: none; /*Outline none*/
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5); /*Box shadow*/
}
</style>