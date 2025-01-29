import { describe, it, expect, beforeEach } from 'vitest';
import { mount } from '@vue/test-utils';
import Mapa from '@/Pages/Mapa.vue';

//Verifica que el componente se haya montado sin errores
// Crear un contenedor para el mapa antes de cada test
beforeEach(() => {
  const mapDiv = document.createElement('div');
  mapDiv.id = 'map';
  document.body.appendChild(mapDiv);
});

describe('Mapa.vue', () => {
  it('debería renderizar el componente correctamente', () => {
    const wrapper = mount(Mapa, {
      props: { products: [] }, // Pasamos props vacías para evitar errores
      attachTo: document.body, // Asegura que se renderiza en el DOM real de jsdom
    });


    //comprueba que el componente se ha renderizado correctamente
    expect(wrapper.exists()).toBe(true);
  });
  it('debería haber un elemento con id "map"', () => {
    // Verificar si el elemento con id "map" está en el DOM
    const mapElement = document.getElementById('map');
    expect(mapElement).not.toBeNull(); // Verifica que no sea null, es decir, que existe
  });
});
