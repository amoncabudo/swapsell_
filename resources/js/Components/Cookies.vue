<script setup>
import { ref, onMounted, watch } from 'vue';
import Cookies from 'js-cookie';

// States of cookie consent
const consentStatus = ref({
  necessaries: true,
  analitiques: false,
  marqueting: false
});

// Configuration of cookie types
const cookieTypes = [
  { 
    nom: 'Cookies Necessàries',  //Cookies necessary
    descripcio: 'Cookies essencials per al funcionament bàsic del lloc web', //Cookies essential for the basic functioning of the website
    key: 'necessaries', //Key
    obligatori: true //Mandatory
  },
  { 
    nom: 'Cookies Analítiques',  //Analytical cookies
    descripcio: 'Cookies que ens ajuden a entendre com interactues amb el lloc web', //Cookies that help us understand how you interact with the website
    key: 'analitiques', //Key
    obligatori: false //Not mandatory
  },
  { 
    nom: 'Cookies de Màrqueting',  //Marketing cookies
    descripcio: 'Cookies utilitzades per personalitzar publicitat i seguiment', //Cookies used to personalize advertising and tracking
    key: 'marqueting', //Key
    obligatori: false //Not mandatory
  }
];

// State of the cookie banner
const mostrarBanner = ref(true);
const mostrarConfiguracio = ref(false);

// Save cookie preferences
const guardarPreferencies = () => {
  // Save cookie configuration to localStorage
  localStorage.setItem('cookieConsent', JSON.stringify(consentStatus.value));
  
  // Establish cookies according to preferences
  if (consentStatus.value.analitiques) {
    Cookies.set('analytics_cookie', 'accepted', { expires: 365 }); //Set the analytics cookie if the user accepts it, it will expire in 365 days
  } else {
    Cookies.remove('analytics_cookie'); //Remove the analytics cookie if the user does not accept it
  }

  if (consentStatus.value.marqueting) {
    Cookies.set('marketing_cookie', 'accepted', { expires: 365 }); //Set the marketing cookie if the user accepts it, it will expire in 365 days
  } else {
    Cookies.remove('marketing_cookie'); //Remove the marketing cookie if the user does not accept it
  }

  // Emit a custom event to notify other components
  window.dispatchEvent(new CustomEvent('cookie-preferences-updated', {
    detail: consentStatus.value
  }));

  // Hide banner
  mostrarBanner.value = false;
  mostrarConfiguracio.value = false;
};

// Accept all cookies
const acceptarTotes = () => {
  consentStatus.value = {
    necessaries: true,
    analitiques: true,
    marqueting: true
  };
  guardarPreferencies();
};

// Reject all non-necessary cookies
const rebutjarTotes = () => {
  consentStatus.value = {
    necessaries: true,
    analitiques: false,
    marqueting: false
  };
  guardarPreferencies();
};

// Initialize cookie consent when mounting
onMounted(() => {
  const guardat = localStorage.getItem('cookieConsent');
  if (guardat) {
    consentStatus.value = JSON.parse(guardat);
    mostrarBanner.value = false;
  }

  // Listen for cookie changes from other windows/tabs
  window.addEventListener('storage', (event) => {
    if (event.key === 'cookieConsent') {
      const nouConsent = JSON.parse(event.newValue);
      consentStatus.value = nouConsent;
      mostrarBanner.value = false;
    }
  });
});

// Synchronize with other tabs when preferences change
watch(consentStatus, (nouValor) => {
  localStorage.setItem('cookieConsent', JSON.stringify(nouValor)); //Save the cookie configuration to localStorage
}, { deep: true });
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
.cookie-banner { /*Cookie banner*/
  position: fixed; /*Fixed position*/
  bottom: 0; /*Bottom position*/
  left: 0; /*Left position*/
  width: 100%; /*Width*/
  transform: none; /*No transform*/
  background-color: rgba(248, 249, 250, 0.95); /*Background color*/
  border-radius: 0; /*No border radius*/
  border-top: 2px solid #007bff; /*Border top*/
  box-shadow: 0 -4px 6px rgba(0,0,0,0.1); /*Box shadow*/
  padding: 20px; /*Padding*/
  z-index: 1000; /*Z-index*/
  display: flex; /*Display*/
  flex-direction: column; /*Flex direction*/
  align-items: center; /*Align items*/
}

.banner-content, .cookie-configuracio { /*Banner content and cookie configuration*/
  max-width: 800px; /*Max width*/
  width: 90%; /*Width*/
}

.banner-actions, .configuracio-actions { /*Banner actions and cookie configuration actions*/
  display: flex; /*Display*/
  justify-content: center; /*Justify content*/
  gap: 15px; /*Gap*/
  margin-top: 15px; /*Margin top*/
  width: 100%; /*Width*/
}

.btn-acceptar, .btn-guardar { /*Accept and save buttons*/
  background-color: #28a745; /*Background color*/
  color: white; /*Color*/
}
.btn-configurar { /*Configure button*/
  background-color: #007bff; /*Background color*/
  color: white; /*Color*/
}
.btn-rebutjar, .btn-cancelar { /*Reject and cancel buttons*/
  background-color: #dc3545; /*Background color*/
  color: white; /*Color*/
}

button { /*Button*/
  padding: 12px 20px; /*Padding*/
  border: none; /*No border*/
  border-radius: 5px; /*Border radius*/
  cursor: pointer; /*Cursor*/
  transition: all 0.3s ease; /*Transition*/
  font-size: 16px; /*Font size*/
  min-width: 120px; /*Min width*/
}

button:hover { /*Button hover*/
  opacity: 0.9; /*Opacity*/
  transform: translateY(-2px); /*Transform*/
  box-shadow: 0 2px 4px rgba(0,0,0,0.2); /*Box shadow*/
}

.cookie-configuracio-overlay { /*Cookie configuration overlay*/
  position: fixed; /*Fixed position*/
  top: 0; /*Top position*/
  left: 0; /*Left position*/
  width: 100%; /*Width*/
  height: 100%; /*Height*/
  background-color: rgba(0, 0, 0, 0.5); /*Background color*/
  display: flex; /*Display*/
  justify-content: center; /*Justify content*/
  align-items: center; /*Align items*/
  z-index: 1100; /*Z-index*/
  padding: 20px; /*Padding*/
}

.cookie-configuracio { /*Cookie configuration*/
  background-color: white; /*Background color*/
  border-radius: 10px; /*Border radius*/
  box-shadow: 0 4px 6px rgba(0,0,0,0.1); /*Box shadow*/
  max-width: 600px; /*Max width*/
  width: 100%; /*Width*/
  padding: 30px; /*Padding*/
  position: relative; /*Position*/
  animation: slideDown 0.3s ease; /*Animation*/
}

.btn-tancar { /*Close button*/
  position: absolute; /*Position*/
  top: 10px; /*Top position*/
  right: 10px; /*Right position*/
  background: none; /*No background*/
  border: none; /*No border*/
  font-size: 24px; /*Font size*/
  cursor: pointer; /*Cursor*/
  color: #dc3545; /*Color*/
  min-width: auto; /*Min width*/
  padding: 0; /*Padding*/
  transition: color 0.3s ease; /*Transition*/
}

.btn-tancar:hover { /*Close button hover*/
  color: #a71d2a; /*Color*/
  transform: none; /*No transform*/
  box-shadow: none; /*No box shadow*/
}

.tipus-cookie { /*Cookie type*/
  margin-bottom: 15px; /*Margin bottom*/
  padding: 15px; /*Padding*/
  background-color: #f1f3f5; /*Background color*/
  border-radius: 5px; /*Border radius*/
  width: 100%; /*Width*/
}

.tipus-cookie label { /*Cookie type label*/
  display: flex; /*Display*/
  align-items: center; /*Align items*/
  font-weight: bold; /*Font weight*/
}

.tipus-cookie input[type="checkbox"] { /*Cookie type input checkbox*/
  margin-right: 10px; /*Margin right*/
  width: 18px; /*Width*/
  height: 18px; /*Height*/
}

@keyframes slideDown { /*Slide down animation*/
  from {
    opacity: 0; /*Opacity*/
    transform: translateY(-50px); /*Transform*/
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive adjustments */
@media (max-width: 600px) {
  .banner-actions, .configuracio-actions { /*Banner actions and cookie configuration actions*/
    flex-direction: column; /*Flex direction*/
    align-items: center; /*Align items*/
  }

  button { /*Button*/
    width: 100%; /*Width*/
    margin-bottom: 10px; /*Margin bottom*/
  }

  .cookie-configuracio { /*Cookie configuration*/
    width: 95%; /*Width*/
    padding: 20px; /*Padding*/
  }
}
</style>
