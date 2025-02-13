<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([ //Create the admin called user
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'admin',
            'role' => '0',
        ]);
        User::factory()->create([ //Create the manager called user
            'name' => 'manager',
            'email' => 'manager@manager.com',
            'password' => 'manager',
            'role' => '1',
        ]);
        User::factory()->create([ //Create the user called user
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => 'user',
            'role' => '2',
        ]);

        //Create the categories
        Category::create([ //Create the category called Home
            'name' => 'Llar',
            'image' => 'HomeCategory.jpg'
        ]);
        Category::create([ //Create the category called Technology
            'name' => 'Tecnologia',
            'image' => 'TecnologyCategory.jpg'
        ]);
        Category::create([ //Create the category called Sports
            'name' => 'Esports',
            'image' => 'SportsCategory.jpg'
        ]);
        Category::create([ //Create the category called Fashion
            'name' => 'Moda',
            'image' => 'ModaCategory.jpg'
        ]);
        Category::create([ //Create the category called Health
            'name' => 'Salut i Bellesa',
            'image' => 'HealthCategory.jpg'
        ]);
        Category::create([ //Create the category called Toys
            'name' => 'Joguines',
            'image' => 'ToysCategory.jpg'
        ]);
        Category::create([ //Create the category called Index
            'name' => 'index',
            'image' => '',
            'id' => 7,
        ]);
        
        //Create the products
        Product::create([ //Create the product Airpods Max
            'name' => 'Airpods Max',
            'user_id' => 0,
            'category_id' => 7,
            'longitude' => '42.273472701885986',
            'latitude' => '2.96470457969817',
            'status' => true,
            'description' => 'Airpods Max',
            'price' => 250,
            'image' => 'airpods.jpg'
        ]);
        Product::create([ //Create the product Manuelles Ajustables
            'name' => 'Manuelles Ajustables',
            'description' => 'Manuelles ajustables',
            'price' => 20,
            'image' => 'mancuernas.jpg',
            'category_id' => 7,
            'user_id' => 0,
            'longitude' => '12',
            'latitude' => '12',
            'status' => true,
        ]);
        Product::create([ //Create the product Dessuadora Chrome Hearts
            'name' => 'Dessuadora Chrome Hearts',
            'description' => 'Dessuadora Chrome Hearts',
            'price' => 650,
            'image' => 'chrome_hearts.png',
            'category_id' => 7,
            'user_id' => 0,
            'longitude' => '12',
            'latitude' => '12',
            'status' => true,
        ]);
        Product::create([ //Create the product Perfum Lignum Vitae
            'name' => 'Perfum Lignum Vitae ',
            'description' => 'Lignum Vitae se inspira en el descubrimiento de John Harrison, relojero del siglo XVII, que utilizó Lignum Vitae en el intrincado funcionamiento de 
            los relojes de mar, ya que la madera es excepcionalmente dura y resistente a la corrosión en el aire salino del mar. Tomando como inspiración el innovador uso de materiales de Harrison, se han mezclado notas inesperadas para producir una fragancia multifacética e intrigante.Los aspectos cálidos y metálicos se acentúan en alusión al apodo de Lignum Vitae, "madera de hierro"; el característico aroma cítrico y picante del propio Lignum Vitae y se hace especial énfasis en el aire salado que provoca la rotura de los cronómetros marinos de latón.El perfume se diseñó como una representación simbólica del intrincado funcionamiento de las máquinas del tiempo de Harrison, pero también como una sugestión del poder del olor para transportarnos a través del tiempo.
            La perfumista Julie Marlowe se encargó de combinar estos aspectos enigmáticos de una manera inesperada e intrigante. ',
            'price' => 80,
            'image' => 'perfume.png',
            'category_id' => 7,
            'user_id' => 0,
            'longitude' => '12',
            'latitude' => '12',
            'status' => true,
        ]); 
    }
}
