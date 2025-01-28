<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';

defineProps({
    isAuthenticated: Boolean,
});

let form = useForm({     
    name: "",
    description: "",
    price: null,
    longitude: null,
    latitude: null,
    status: "active",
    category: "",
    image: null
});

// Método para manejar la subida de archivos
function handleFileUpload(event) {
    form.image = event.target.files[0]; // Guardamos el archivo en la propiedad 'image'
}

</script>

<template>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-2xl mx-auto">
        <!-- Encabezado del formulario -->
        <div class="text-center mb-8">
          <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
            Publica tu Producto
          </h1>
          <p class="text-gray-600">
            Completa los detalles de tu producto para publicarlo
          </p>
        </div>

        <!-- Tarjeta del formulario -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
          <form @submit.prevent="form.post(route('sell'))" enctype="multipart/form-data" class="space-y-6">
            <!-- Campo Nombre -->
            <div class="form-group">
              <label for="name" class="form-label">Nombre del Producto</label>
              <div class="relative">
                <input
                  type="text"
                  id="name"
                  v-model="form.name"
                  class="form-input pl-10"
                  required
                />
                <span class="form-icon">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Campo Descripción -->
            <div class="form-group">
              <label for="description" class="form-label">Descripción</label>
              <div class="relative">
                <textarea
                  id="description"
                  v-model="form.description"
                  rows="4"
                  class="form-input"
                  required
                ></textarea>
              </div>
            </div>

            <!-- Campo Imagen -->
            <div class="form-group">
            <label for="image" class="form-label">Imagen del Producto</label>
              <div class="relative">
                <input
                  type="file"
                  id="image"
                  @change="handleFileUpload"
                  required
                />
              </div>
            </div>

            <!-- Campo Precio -->
            <div class="form-group">
              <label for="price" class="form-label">Precio (€)</label>
              <div class="relative">
                <input
                  type="number"
                  id="price"
                  v-model="form.price"
                  class="form-input pl-10"
                  required
                />
                <span class="form-icon">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Campos de Ubicación -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="form-group">
                <label for="longitude" class="form-label">Longitud</label>
                <div class="relative">
                  <input
                    type="number"
                    step="any"
                    id="longitude"
                    v-model="form.longitude"
                    class="form-input pl-10"
                    required
                  />
                  <span class="form-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label for="latitude" class="form-label">Latitud</label>
                <div class="relative">
                  <input
                    type="number"
                    step="any"
                    id="latitude"
                    v-model="form.latitude"
                    class="form-input pl-10"
                    required
                  />
                  <span class="form-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                  </span>
                </div>
              </div>
            </div>

            <!-- Campo Estado -->
            <div class="form-group">
              <label for="status" class="form-label">Estado</label>
              <div class="relative">
                <select
                  id="status"
                  v-model="form.status"
                  class="form-input pl-10"
                  required
                >
                  <option value="active">Activo</option>
                  <option value="inactive">Inactivo</option>
                </select>
                <span class="form-icon">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </div>
            </div>
            <!--category-->
            <div class="form-group">
              <label for="category" class="form-label">Categoría</label>
              <div class="relative">
                <select
                  id="category_id"
                  v-model="form.category_id"
                  class="form-input pl-10"
                  required
                >
                  <option value="1">Hogar</option>
                  <option value="2">Tecnología</option>
                  <option value="3">Deportes</option>
                  <option value="4">Moda</option>
                  <option value="5">Salud y belleza</option>
                  <option value="6">Juguetes</option>
                </select>
                <span class="form-icon">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </div>
            </div>
            <!-- Botón de envío -->
            <div class="mt-8">
              <button
                type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold py-3 px-4 rounded-xl
                       hover:from-blue-600 hover:to-indigo-700 transform hover:-translate-y-0.5 transition-all duration-150
                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Publicar Producto
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </component>
</template>

<style scoped>
/* Estilos del formulario */
.form-group {
  margin-top: 0.25rem;
}

.form-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 500;
  color: rgb(55, 65, 81);
}

.form-input {
  display: block;
  width: 100%;
  border-radius: 0.5rem;
  border-color: rgb(209, 213, 219);
  box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  padding-left: 3rem;
  color: rgb(17, 24, 39);
  transition-property: color, background-color, border-color;
  transition-duration: 200ms;
}

.form-input:focus {
  border-color: rgb(59, 130, 246);
  outline: 2px solid rgb(59, 130, 246);
}

.form-icon {
  position: absolute;
  left: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  color: rgb(156, 163, 175);
  pointer-events: none;
  border-right: 1px solid rgb(209, 213, 219);
  padding-right: 0.75rem;
}

/* Estilos específicos para el textarea */
textarea.form-input {
  padding-left: 3rem;
  resize: none;
  color: rgb(17, 24, 39);
}

/* Deshabilitar flechas en inputs numéricos */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Efectos y animaciones */
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

/* Efecto glass */
.glass-effect {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 8px 32px 0 rgba(0, 66, 102, 0.2);
}

/* Colores personalizados */
.bg-custom-blue {
  background-color: #004266;
}

.text-custom-blue {
  color: #004266;
}

/* Estilos para el botón de envío */
.submit-button {
  width: 100%;
  background: linear-gradient(to right, #004266, #006699);
  color: white;
  font-weight: bold;
  padding: 0.75rem 1rem;
  border-radius: 0.75rem;
  transition: background 0.15s ease-in-out;
}

.submit-button:hover {
  background: linear-gradient(to right, #00334d, #00527a);
  transform: translateY(-0.25rem);
}

.submit-button:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
}
</style>

