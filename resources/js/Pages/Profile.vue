<template>
  <div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto p-6">
      <div class="text-left mb-8">
        <h1 class="text-4xl font-bold">PERFIL USUARI</h1>
      </div>

      <div class="flex items-center mb-10">
        <div class="w-24 h-24 rounded-lg"><img src="/images/User.png" alt="User_Image" width="500" height="600"></div>
        <div class="ml-6">
          <h2 class="text-2xl font-semibold">NOM</h2>
          <p class="text-gray-600">DESCRIPCIÓ</p>
          <p class="text-gray-600">UBICACIÓ</p>
        </div>
      </div>

      <div class="products">
            <h3 class="text-2xl font-semibold mb-6">Productes</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="flex flex-col items-center p-6 bg-gray-200 shadow-md rounded-lg ">
                    <div class="w-21 h-21 rounded-lg mb-4"><img src="/images/machine.jpg" alt="Product_Image" width="250" height="300"></div>
                    <span class="text-lg font-medium">Producte1</span>
                </div>
                <div class="flex flex-col items-center p-6 bg-gray-200 shadow-md rounded-lg">
                    <div class="w-21 h-21 rounded-lg mb-4"><img src="/images/machine.jpg" alt="Product_Image" width="250" height="300"></div>
                    <span class="text-lg font-medium">Producte2</span>
                </div>
                <div class="flex flex-col items-center p-6 bg-gray-200 shadow-md rounded-lg">
                    <div class="w-21 h-21 rounded-lg mb-4"><img src="/images/machine.jpg" alt="Product_Image" width="250" height="300"></div>
                    <span class="text-lg font-medium">Producte3</span>
                </div>
                <div class="flex flex-col items-center p-6 bg-gray-200 shadow-md rounded-lg ">
                    <div class="w-21 h-21 rounded-lg mb-4"><img src="/images/machine.jpg" alt="Product_Image" width="250" height="300"></div>
                    <span class="text-lg font-medium">Producte4</span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-9">
                <div class="flex flex-col items-center p-6 bg-gray-200 shadow-md rounded-lg">
                    <div class="w-21 h-21 rounded-lg mb-4"><img src="/images/machine.jpg" alt="Product_Image" width="250" height="300"></div>
                    <span class="text-lg font-medium">Producte5</span>
                </div>
                <div class="flex flex-col items-center p-6 bg-gray-200 shadow-md rounded-lg">
                    <div class="w-21 h-21 rounded-lg mb-4"><img src="/images/machine.jpg" alt="Product_Image" width="250" height="300"></div>
                    <span class="text-lg font-medium">Producte6</span>
                </div>
                <div class="flex flex-col items-center p-6 bg-gray-200 shadow-md rounded-lg">
                    <div class="w-21 h-21 rounded-lg mb-4"><img src="/images/machine.jpg" alt="Product_Image" width="250" height="300"></div>
                    <span class="text-lg font-medium">Producte7</span>
                </div>
                <div class="flex flex-col items-center p-6 bg-gray-200 shadow-md rounded-lg">
                    <div class="w-21 h-21 rounded-lg mb-4"><img src="/images/machine.jpg" alt="Product_Image" width="250" height="300"></div>
                    <span class="text-lg font-medium">Producte8</span>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>