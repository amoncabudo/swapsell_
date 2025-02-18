<template>
    <div class="mb-4">
        <label for="statusFilter" class="block text-sm font-medium text-gray-700">Filtrar por estado:</label>
        <select id="statusFilter" v-model="selectedStatus" @change="filterProducts" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
            <option value="">Tots</option>
            <option value="1">Disponible</option>
            <option value="0">Venut</option>
        </select>
    </div>
</template>

<script>
export default {
    data() {
        return {
            selectedStatus: ''
        };
    },
    methods: {
        filterProducts() {
            console.log('Estado seleccionado:', this.selectedStatus);
            this.$emit('filter', this.selectedStatus);
        }
    }
};
</script> 