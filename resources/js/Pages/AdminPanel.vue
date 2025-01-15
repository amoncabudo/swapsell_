<template>
    <div class="min-h-screen bg-gray-100">
        <div class="container mx-auto p-6">
            <h1 class="text-4xl font-bold text-center mb-8">PANELL D'ADMINISTRADOR</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="" alt="AddUsers" class="mx-auto mb-2 w-12 h-12" />
                    <h2 class="text-xl font-semibold mb-2">AFEGIR USUARIS</h2>
                    <p>Apartat per afegir usuaris</p>
                    <div class="mt-4">
                        <button class="bg-SubastaButton1 text-white px-4 py-2 rounded hover:bg-blue-600">+</button>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="" alt="AddEvents" class="mx-auto mb-2 w-12 h-12" />
                    <h2 class="text-xl font-semibold mb-2">AFEGIR ESDEVENIMENTS</h2>
                    <p>Apartat per afegir esdeveniments</p>
                    <div class="mt-4">
                        <button class="bg-SubastaButton1 text-white px-4 py-2 rounded hover:bg-blue-600">+</button>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="" alt="AddProducts" class="mx-auto mb-2 w-12 h-12" />
                    <h2 class="text-xl font-semibold mb-2">AFEGIR PRODUCTES</h2>
                    <p>Apartat per afegir productes</p>
                    <div class="mt-4">
                        <button class="bg-SubastaButton1 text-white px-4 py-2 rounded hover:bg-blue-600">+</button>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="" alt="ListUsers" class="mx-auto mb-2 w-12 h-12" />
                    <h2 class="text-xl font-semibold mb-2">LLISTA D'USUARIS</h2>
                    <p>Apartat per visualitzar i gestionar els usuaris</p>
                    <div class="mt-4">
                        <button
                            class="bg-SubastaButton1 text-white px-4 py-2 rounded hover:bg-blue-600">Accedir</button>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="" alt="ListEvents" class="mx-auto mb-2 w-12 h-12" />
                    <h2 class="text-xl font-semibold mb-2">LLISTA D'ESDEVENIMENTS</h2>
                    <p>Apartat per visualitzar i gestionar els esdeveniments</p>
                    <div class="mt-4">
                        <button
                            class="bg-SubastaButton1 text-white px-4 py-2 rounded hover:bg-blue-600">Accedir</button>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="" alt="ListProducts" class="mx-auto mb-2 w-12 h-12" />
                    <h2 class="text-xl font-semibold mb-2">LLISTA DE PRODUCTES</h2>
                    <p>Apartat per visualitzar i gestionar els productes</p>
                    <div class="mt-4">
                        <button
                            class="bg-SubastaButton1 text-white px-4 py-2 rounded hover:bg-blue-600">Accedir</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AdminPanel',
}
</script>

<style scoped>
/* Estilos adicionales si es necesario */
</style>
