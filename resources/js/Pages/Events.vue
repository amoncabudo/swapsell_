<template>
    <div class="bg-white">
      <main class="p-8">
        <h1 class="text-4xl font-bold text-center mb-8">ESDEVENIMENTS</h1>
        
        <div class="space-y-4">
          <div 
            v-for="product in products" 
            :key="product.id" 
            class="bg-gray-100 border p-4 shadow-md mb-6"
          >
            <h2 class="text-lg font-semibold">{{ product.name }}</h2>
            <div class="flex justify-between text-sm text-gray-600 mt-2">
              <span>Ubicació</span>
              <span>Data de l'esdeveniment</span>
              <span>Tipus d'esdeveniment</span>
              <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 
              focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 
              dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none 
              dark:focus:ring-blue-800">ACCEDIR-HI</button>
            </div>
          </div>
        </div>
      </main>
    </div>
    <Footer />
  </template>
  
  <script>
  import Footer from "../Components/Footer.vue";
  export default {
    data() {
      return {
        products: [
          { id: 1, image: 'https://via.placeholder.com/150', name: 'Esdeveniment 1', description: 'Descripción breve del producte 1.', price: '$19.99' },
          { id: 2, image: 'https://via.placeholder.com/150', name: 'Esdeveniment 2', description: 'Descripción breve del producte 2.', price: '$29.99' },
          { id: 3, image: 'https://via.placeholder.com/150', name: 'Esdeveniment 3', description: 'Descripción breve del producte 3.', price: '$39.99' },
          { id: 4, image: 'https://via.placeholder.com/150', name: 'Esdeveniment 4', description: 'Descripción breve del producte 4.', price: '$49.99' },
          { id: 5, image: 'https://via.placeholder.com/150', name: 'Esdeveniment 5', description: 'Descripción breve del producte 5.', price: '$59.99' },
          { id: 6, image: 'https://via.placeholder.com/150', name: 'Esdeveniment 6', description: 'Descripción breve del producte 6.', price: '$69.99' },
          { id: 7, image: 'https://via.placeholder.com/150', name: 'Esdeveniment 7', description: 'Descripción breve del producte 7.', price: '$79.99' },
          { id: 8, image: 'https://via.placeholder.com/150', name: 'Esdeveniment 8', description: 'Descripción breve del producte 8.', price: '$89.99' }
        ]
      };
    },
    components: {
        Footer,
    }
  };
  </script>
  
  <style scoped>
  </style>

