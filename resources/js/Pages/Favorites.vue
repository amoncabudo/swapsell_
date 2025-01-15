<template>
    <div class="bg-white">
      <main class="p-8">
        <h1 class="text-4xl font-bold text-center mb-8">FAVORITOS</h1>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
          <div 
            v-for="product in products" 
            :key="product.id" 
            class="bg-white border p-4 text-center shadow-md"
          >
            <img 
              :src="product.image" 
              :alt="product.name" 
              class="w-full h-32 object-cover mb-4"
            >
            <h2 class="text-lg font-semibold">{{ product.name }}</h2>
            <p class="text-sm text-gray-600">{{ product.description }}</p>
            <p class="text-lg font-bold mt-2">{{ product.price }}</p>
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
          { id: 1, image: 'https://via.placeholder.com/150', name: 'Producto 1', description: 'Descripción breve del producto 1.', price: '$19.99' },
          { id: 2, image: 'https://via.placeholder.com/150', name: 'Producto 2', description: 'Descripción breve del producto 2.', price: '$29.99' },
          { id: 3, image: 'https://via.placeholder.com/150', name: 'Producto 3', description: 'Descripción breve del producto 3.', price: '$39.99' },
          { id: 4, image: 'https://via.placeholder.com/150', name: 'Producto 4', description: 'Descripción breve del producto 4.', price: '$49.99' },
          { id: 5, image: 'https://via.placeholder.com/150', name: 'Producto 5', description: 'Descripción breve del producto 5.', price: '$59.99' },
          { id: 6, image: 'https://via.placeholder.com/150', name: 'Producto 6', description: 'Descripción breve del producto 6.', price: '$69.99' },
          { id: 7, image: 'https://via.placeholder.com/150', name: 'Producto 7', description: 'Descripción breve del producto 7.', price: '$79.99' },
          { id: 8, image: 'https://via.placeholder.com/150', name: 'Producto 8', description: 'Descripción breve del producto 8.', price: '$89.99' }
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
