<script setup>
import { ref, onMounted } from 'vue';

const isDark = ref(localStorage.getItem('theme') === 'dark');

// Aplicar el modo oscuro según la preferencia guardada
onMounted(() => {
  if (isDark.value) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
});

// Alternar el modo oscuro
const toggleDarkMode = () => {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  }
};
</script>

<template>
  <button @click="toggleDarkMode" class="p-2 text-black dark:text-white">
    <span  v-if="isDark">☀️</span>
    <span v-else>🌙</span>
</button>
</template>
