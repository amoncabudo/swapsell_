<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
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

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'admin',
        ]);

        // Aquí tu código para crear categorías
        Category::create([
            'name' => 'Hogar'
        ]);
        Category::create([
            'name' => 'Tecnología'
        ]);
        Category::create([
            'name' => 'Deportes'
        ]);
        Category::create([
            'name' => 'Moda'
        ]);
        Category::create([
            'name' => 'Salud y belleza'
        ]);
        Category::create([
            'name' => 'Juguetes'
        ]);
        
    }
}
