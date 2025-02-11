<script setup>
import { ref, onMounted } from 'vue';
import Cookies from 'js-cookie';

// Estats de consentiment de cookies
const consentStatus = ref({
  necessaries: true,
  analitiques: false,
  marqueting: false
});

// Configuració de tipus de cookies
const cookieTypes = [
  { 
    nom: 'Cookies Necessàries', 
    descripcio: 'Cookies essencials per al funcionament bàsic del lloc web',
    key: 'necessaries',
    obligatori: true
  },
  { 
    nom: 'Cookies Analítiques', 
    descripcio: 'Cookies que ens ajuden a entendre com interactues amb el lloc web',
    key: 'analitiques',
    obligatori: false
  },
  { 
    nom: 'Cookies de Màrqueting', 
    descripcio: 'Cookies utilitzades per personalitzar publicitat i seguiment',
    key: 'marqueting',
    obligatori: false
  }
];

// Estat del banner de cookies
const mostrarBanner = ref(true);
const mostrarConfiguracio = ref(false);

// Guardar preferències de cookies
const guardarPreferencies = () => {
  // Guardar configuració de cookies a localStorage
  localStorage.setItem('cookieConsent', JSON.stringify(consentStatus.value));
  
  // Establir cookies segons preferències
  if (consentStatus.value.analitiques) {
    Cookies.set('analytics_cookie', 'accepted', { expires: 365 });
  } else {
    Cookies.remove('analytics_cookie');
  }

  if (consentStatus.value.marqueting) {
    Cookies.set('marketing_cookie', 'accepted', { expires: 365 });
  } else {
    Cookies.remove('marketing_cookie');
  }

  // Amagar banner
  mostrarBanner.value = false;
  mostrarConfiguracio.value = false;
};

// Acceptar totes les cookies
const acceptarTotes = () => {
  consentStatus.value = {
    necessaries: true,
    analitiques: true,
    marqueting: true
  };
  guardarPreferencies();
};

// Rebutjar cookies no necessàries
const rebutjarTotes = () => {
  consentStatus.value = {
    necessaries: true,
    analitiques: false,
    marqueting: false
  };
  guardarPreferencies();
};

// Inicialitzar estat de consentiment en muntar
onMounted(() => {
  const guardat = localStorage.getItem('cookieConsent');
  if (guardat) {
    consentStatus.value = JSON.parse(guardat);
    mostrarBanner.value = false;
  }
});
</script>

<template>
  <div v-if="mostrarBanner" class="cookie-banner">
    <div class="banner-content">
      <h3 class="text-black">🍪 Gestió de Cookies</h3>
      <p class="text-black">Fem servir cookies per millorar la teva experiència de navegació. Ens permets usar cookies?</p>
      
      <div class="banner-actions">
        <button @click="acceptarTotes" class="btn-acceptar">Acceptar Totes</button>
        <button @click="mostrarConfiguracio = true" class="btn-configurar">Configurar</button>
        <button @click="rebutjarTotes" class="btn-rebutjar">Rebutjar</button>
      </div>
    </div>
  </div>

  <div v-if="mostrarConfiguracio" class="cookie-configuracio-overlay">
    <div class="cookie-configuracio">
      <button class="btn-tancar" @click="mostrarConfiguracio = false">✕</button>
      <h3 class="text-black">Configuració de Cookies</h3>
      <div v-for="tipus in cookieTypes" :key="tipus.key" class="tipus-cookie">
        <label>
          <input 
            type="checkbox" 
            :checked="consentStatus[tipus.key]" 
            :disabled="tipus.obligatori"
            @change="consentStatus[tipus.key] = !consentStatus[tipus.key]"
          >
          <p class="text-black">{{ tipus.nom }}</p>
        </label>
        <p class="text-black">{{ tipus.descripcio }}</p>
      </div>
      <div class="configuracio-actions">
        <button @click="guardarPreferencies" class="btn-guardar">Guardar Preferències</button>
        <button @click="mostrarConfiguracio = false" class="btn-cancelar">Cancel·lar</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.cookie-banner {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  transform: none;
  background-color: rgba(248, 249, 250, 0.95);
  border-radius: 0;
  border-top: 2px solid #007bff;
  box-shadow: 0 -4px 6px rgba(0,0,0,0.1);
  padding: 20px;
  z-index: 1000;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.banner-content, .cookie-configuracio {
  max-width: 800px;
  width: 90%;
}

.banner-actions, .configuracio-actions {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 15px;
  width: 100%;
}

.btn-acceptar, .btn-guardar { 
  background-color: #28a745; 
  color: white; 
}
.btn-configurar { 
  background-color: #007bff; 
  color: white; 
}
.btn-rebutjar, .btn-cancelar { 
  background-color: #dc3545; 
  color: white; 
}

button {
  padding: 12px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 16px;
  min-width: 120px;
}

button:hover {
  opacity: 0.9;
  transform: translateY(-2px);
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.cookie-configuracio-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1100;
  padding: 20px;
}

.cookie-configuracio {
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  max-width: 600px;
  width: 100%;
  padding: 30px;
  position: relative;
  animation: slideDown 0.3s ease;
}

.btn-tancar {
  position: absolute;
  top: 10px;
  right: 10px;
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #dc3545;
  min-width: auto;
  padding: 0;
  transition: color 0.3s ease;
}

.btn-tancar:hover {
  color: #a71d2a;
  transform: none;
  box-shadow: none;
}

.tipus-cookie {
  margin-bottom: 15px;
  padding: 15px;
  background-color: #f1f3f5;
  border-radius: 5px;
  width: 100%;
}

.tipus-cookie label {
  display: flex;
  align-items: center;
  font-weight: bold;
}

.tipus-cookie input[type="checkbox"] {
  margin-right: 10px;
  width: 18px;
  height: 18px;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-50px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive adjustments */
@media (max-width: 600px) {
  .banner-actions, .configuracio-actions {
    flex-direction: column;
    align-items: center;
  }

  button {
    width: 100%;
    margin-bottom: 10px;
  }

  .cookie-configuracio {
    width: 95%;
    padding: 20px;
  }
}
</style>
