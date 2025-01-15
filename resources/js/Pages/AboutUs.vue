<template>
    <div class="bg-white">
      <main class="p-8">
        <h1 class="text-4xl font-bold text-center mb-8">Sobre Nosaltres</h1>
        <div class="info-section">
            <p>Somos un grupo de estudiantes apasionados de 2º de Desarrollo de Aplicaciones Web (DAW), 
                comprometidos con aprender, crear e innovar en el mundo digital. Actualmente, 
                formamos parte del Proyecto 4: Let's Code for a Better World, un desafío que nos impulsa 
                a desarrollar soluciones tecnológicas con impacto positivo en nuestra sociedad.
                </p>
                <br>
               <p> En esta ocasión, estamos trabajando en una plataforma de venta de segunda mano, 
                diseñada para fomentar la sostenibilidad, la reutilización y la colaboración entre 
                personas.
                 Nuestro objetivo es ofrecer una experiencia intuitiva, versátil y segura para que los 
                usuarios puedan: </p>
                <p>Crear y listar productos que ya no necesitan.</p>
                <p>Editar o actualizar la información de los artículos publicados.</p>
                <p>Eliminar publicaciones fácilmente cuando ya no estén disponibles.</p>
                <p>Comprar productos de calidad a precios accesibles.</p>
                <p>Vender de manera rápida y eficiente, conectando con compradores potenciales.</p>
                <p>Subastar artículos, añadiendo una capa emocionante e interactiva al proceso de venta.</p>
                <p>Participar activamente en nuestra comunidad, haciendo de la aplicación un espacio inclusivo y dinámico.</p>
                <p>Nuestro proyecto refleja nuestra visión de cómo la tecnología puede ser una herramienta poderosa para generar cambio, promoviendo un estilo de vida más consciente y sostenible. Estamos emocionados de compartir esta aventura contigo y seguir aprendiendo mientras construimos juntos un mundo mejor.</p>
        </div>
      
      
        <!-- Cards Grid -->
        <h2 class="text-2xl font-bold text-center mb-4">Equipo</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
          <div 
            v-for="member in teamMembers" 
            :key="member.id"
            class="card-container relative"
          >
            <div class="card transform transition-transform duration-700">
              <!-- Front Card -->
              <div class="card-front bg-white border p-4 text-center shadow-md">
                <div class="w-32 h-32 mx-auto mb-4 rounded-full overflow-hidden">
                  <img 
                    :src="member.image" 
                    :alt="`${member.name} ${member.surname}`" 
                    class="w-full h-full object-cover"
                  >
                </div>
                <h2 class="text-lg font-semibold">{{ member.name }} {{ member.surname }}</h2>
                <p class="text-sm text-gray-600">{{ member.role }}</p>
                <p class="text-sm text-gray-600">{{ member.rank }}</p>
              </div>
              <!-- Back Card -->
              <div class="card-back bg-gray-100 border p-4 text-center shadow-md flex items-center justify-center">
                <div>
                  <h2 class="text-lg font-semibold">{{ member.name }}</h2>
                  <p class="text-sm text-gray-600 mb-2">{{ member.role }}</p>
                  <p class="text-sm text-gray-600">{{ member.rank }}</p>
                  <a href="#" class="text-blue-500 hover:underline">Ver más</a>
                </div>
              </div>
            </div>
          </div>          
        </div>
      </main>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        teamMembers: [
          { id: 1, name: 'Arnau', surname: 'Garcia', role: 'Programador Full Stack', rank: '', image: 'img/logo1.png' },
          { id: 2, name: 'Marc', surname: 'Cufí', role: 'Programador Full Stack', rank: '', image: 'img/logo2.png' },
          { id: 3, name: 'Alex', surname: 'Moncayo', role: 'Programador Full Stack', rank: '', image: 'img/logo3.png' },
          { id: 4, name: 'Joel', surname: 'Pages', role: 'Programador Full Stack', rank: 'Scrum Master', image: 'img/logo4.png' },
          { id: 5, name: 'Carla', surname: 'De Caprio', role: 'Programador Full Stack', rank: '', image: 'img/logo5.png' },
          { id: 6, name: 'Jhosep', surname: 'Espinoza ', role: 'Programador Full Stack', rank: '', image: 'img/logo6.png' },
        ]
      };
    }
  }
  </script>
  
  <style scoped>
  /* Card Container*/
  .card-container {
    perspective: 1000px;
    width: 100%;
    position: relative;
  }
  
  /* Card styles */
  .card {
    width: 100%;
    height: 100%;
    transform-style: preserve-3d;
    transition: transform 0.7s ease;
    position: relative;
  }
  .card-front,
  .card-back {
    backface-visibility: hidden;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }
    .card-back {
    transform: rotateY(180deg);
  }
    .card-container:hover .card {
    transform: rotateY(180deg);
  }

  .info-section {
    background-color: #f9f9f9;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 5px;
    text-align: center;
  }

  .info-section h2 {
    margin-bottom: 10px;
    font-size: 24px;
  }

  .info-section p {
    margin: 5px 0;
    font-size: 16px;
  }
  </style>
  