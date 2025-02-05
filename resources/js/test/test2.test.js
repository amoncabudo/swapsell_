import { describe, it, expect, vi } from 'vitest';
import { shallowMount } from '@vue/test-utils';
import Products from '@/Pages/Products.vue';
import axios from 'axios';

vi.mock('axios');
vi.stubGlobal('route', () => '/categories.trending');

describe('Products.vue', () => {
  it('fetchCategories hauria d\'obtenir i actualitzar les categories excloent la id 7', async () => {
    axios.get.mockResolvedValue({ data: [
      { id: 1, name: 'Llar' },
      { id: 7, name: 'Excluïda' },
      { id: 3, name: 'Tecnologia' }
    ] });

    const wrapper = shallowMount(Products);
    await wrapper.vm.fetchCategories();

    expect(axios.get).toHaveBeenCalledWith('/categories.trending');
    expect(wrapper.vm.categories).toEqual([
      { id: 1, name: 'Llar' },
      { id: 3, name: 'Tecnologia' }
    ]);
  });
});
