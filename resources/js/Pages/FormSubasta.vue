<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { watch, ref } from 'vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    isAuthenticated: Boolean,
    userProducts: Array
});

const dateError = ref('');

let form = useForm({     
    product_id: null,
    start_price: null,
    current_price: null, // This should match the start_price initially
    start_time: null,
    end_time: null,
    status: true
});

// Add a watcher to update current_price when start_price changes
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
  <Head title = "Formulari subhaste"></Head>
  <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
          <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
            Crear nova subhasta
          </h1>
          <p class="text-gray-600">
            Selecciona un producte, estableix el preu inicial i les dates de la subhasta.
          </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
          <form @submit.prevent="form.post(route('auctions.store'), {
              onSuccess: () => {
                  // Redirect or show success message
              },
              onError: (errors) => {
                  console.error(errors);
              }
          })" class="space-y-6">
            <!-- Selector de Producto -->
            <div class="form-group">
              <label for="product" class="form-label">Selecciona el Producte</label>
              <div class="relative">
                <select
                  id="product"
                  v-model="form.product_id"
                  class="form-input pl-10"
                  required
                >
                  <option value="">Selecciona un producte</option>
                  <option v-for="product in userProducts" 
                          :key="product.id" 
                          :value="product.id">
                    {{ product.name }} - {{ product.price }}€
                  </option>
                </select>
                <span class="form-icon">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Precio Inicial -->
            <div class="form-group">
              <label for="start_price" class="form-label">Preu Inicial (€)</label>
              <div class="relative">
                <input
                  type="number"
                  id="start_price"
                  v-model="form.start_price"
                  class="form-input pl-10"
                  required
                  min="0"
                  step="0.01"
                />
                <span class="form-icon">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Fecha y Hora de Inicio -->
            <div class="form-group">
              <label for="start_time" class="form-label">Inici de la Subhasta</label>
              <div class="relative">
                <input
                  type="datetime-local"
                  id="start_time"
                  v-model="form.start_time"
                  class="form-input pl-10"
                  required
                />
                <span class="form-icon">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </span>
              </div>
            </div>

            <!-- Fecha y Hora de Fin -->
            <div class="form-group">
              <label for="end_time" class="form-label">Fi de la Subhasta</label>
              <div class="relative">
                <input
                  type="datetime-local"
                  id="end_time"
                  v-model="form.end_time"
                  class="form-input pl-10"
                  :min="form.start_time"
                  required
                />
                <span class="form-icon">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </div>
              <p v-if="dateError" class="text-red-500 text-sm mt-1">{{ dateError }}</p>
            </div>

            <!-- Botón de envío -->
            <div class="mt-8">
              <button
                type="submit"
                class="submit-button"
              >
                Crear Subhasta
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </component>
</template>

<style scoped>
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
  border: 1px solid rgb(209, 213, 219);
  padding: 0.5rem 1rem;
  transition: all 0.15s ease;
}

.form-input:focus {
  outline: none;
  border-color: rgb(59, 130, 246);
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.submit-button {
  width: 100%;
  background: linear-gradient(to right, #004266, #006699);
  color: white;
  font-weight: bold;
  padding: 0.75rem 1rem;
  border-radius: 0.75rem;
  transition: all 0.15s ease-in-out;
}

.submit-button:hover {
  background: linear-gradient(to right, #00334d, #00527a);
  transform: translateY(-0.25rem);
}

.form-label {
  color: #000000;
  font-weight: 500;
}

.form-input, 
select,
input {
  color: #000000;
}

h1 {
  color: #000000 !important;
}

p {
  color: #000000 !important;
}

.form-group {
  color: #000000;
}

/* Estilos personalizados para inputs de tipo fecha/hora */
input[type="datetime-local"] {
  appearance: none;
  background-color: white;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  color: #1a202c;
  font-size: 0.875rem;
  padding: 0.625rem 1rem;
  width: 100%;
  transition: all 0.2s;
}

input[type="datetime-local"]:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  outline: none;
}

/* Estilizar el icono del calendario */
input[type="datetime-local"]::-webkit-calendar-picker-indicator {
  background: transparent;
  bottom: 0;
  color: transparent;
  cursor: pointer;
  height: auto;
  left: auto;
  position: absolute;
  right: 0.5rem;
  top: 50%;
  transform: translateY(-50%);
  width: 1.5rem;
}

input[type="datetime-local"]::-webkit-calendar-picker-indicator:hover {
  opacity: 0.7;
}

/* Contenedor del campo de fecha con icono personalizado */
.date-input-container {
  position: relative;
}

.date-input-container::after {
  content: '';
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'%3E%3C/path%3E%3C/svg%3E");
  background-position: center;
  background-repeat: no-repeat;
  background-size: contain;
  height: 1.25rem;
  pointer-events: none;
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  width: 1.25rem;
}

/* Ajustes específicos para el campo de fecha/hora */
.form-input[type="datetime-local"] {
  background-color: white;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  color: #1a202c;
  font-size: 0.875rem;
  padding: 0.625rem 1rem;
  padding-right: 2.5rem;
  width: 100%;
}

.form-input[type="datetime-local"]::-webkit-calendar-picker-indicator {
  opacity: 0;
}

.text-red-500 {
  color: #ef4444;
}

/* Ajustes para el calendario */
.form-icon {
  position: absolute;
  left: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  color: rgb(156, 163, 175);
  pointer-events: all; /* Cambiado de none a all para permitir clicks */
  border-right: 1px solid rgb(209, 213, 219);
  padding-right: 1rem;
  margin-right: 0.75rem;
  cursor: pointer; /* Añadido cursor pointer */
  z-index: 10; /* Asegurar que el icono esté por encima */
}

/* Ocultar el indicador nativo del calendario */
input[type="datetime-local"]::-webkit-calendar-picker-indicator {
  opacity: 0;
  width: 100%;
  height: 100%;
  position: absolute;
  left: 0;
  top: 0;
  cursor: pointer;
}

/* Ajuste del contenedor para manejar eventos */
.relative {
  display: flex;
  align-items: center;
  position: relative;
}

.relative input[type="datetime-local"] {
  padding-left: 3.5rem !important;
  cursor: pointer;
}

/* Efecto hover en el icono */
.form-icon:hover {
  color: #3b82f6;
  transition: color 0.2s ease;
}

/* Ajustar el padding del input para el icono */
.form-input {
  padding-left: 3rem !important; /* Aumentado de 2.5rem a 3rem */
}

/* Asegurar que el icono no interfiera con el selector */
select.form-input {
  padding-right: 2rem;
  appearance: none;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
}

/* Ajustes específicos para input datetime-local */
input[type="datetime-local"] {
  padding-left: 3.5rem !important; /* Específico para inputs de fecha/hora */
  min-width: 200px; /* Asegura un ancho mínimo */
}

/* Asegurar que el contenido no se solape */
.relative {
  display: flex;
  align-items: center;
}

.relative input {
  flex-grow: 1;
}

/* Ajuste del espacio para el icono del calendario nativo */
input[type="datetime-local"]::-webkit-calendar-picker-indicator {
  margin-left: auto;
  margin-right: 0.5rem;
}

/* Ajustes comunes para todos los inputs */
.form-input {
  padding-left: 3.5rem !important;
  min-width: 200px;
}

/* Ajustes específicos para el select de productos */
select.form-input {
  padding-left: 3.5rem !important;
  padding-right: 2.5rem;
  background-position: right 0.75rem center;
}

/* Ajustes específicos para el input de precio */
input[type="number"].form-input {
  padding-left: 3.5rem !important;
}

/* Ajustes del contenedor relativo */
.relative {
  display: flex;
  align-items: center;
  position: relative;
}

.form-icon {
  position: absolute;
  left: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  color: rgb(156, 163, 175);
  pointer-events: none;
  border-right: 1px solid rgb(209, 213, 219);
  padding-right: 1rem;
  margin-right: 0.75rem;
  height: 100%;
  display: flex;
  align-items: center;
}

/* Ajuste para el icono del calendario nativo */
input[type="datetime-local"]::-webkit-calendar-picker-indicator {
  margin-left: auto;
  margin-right: 0.75rem;
}
</style>