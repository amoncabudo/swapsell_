import { ref } from 'vue';

export const cartCount = ref(0);

export function incrementCartCount() {
  cartCount.value++;
}

export function decrementCartCount() {
  if (cartCount.value > 0) {
    cartCount.value--;
  }
}