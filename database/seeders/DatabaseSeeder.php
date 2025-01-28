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
            'role' => '0',
        ]);

        // Aquí tu código para crear categorías
        Category::create([
            'name' => 'Hogar',
            'image' => 'https://cdn.pixabay.com/photo/2016/11/18/17/46/architecture-1836070_960_720.jpg'
        ]);
        Category::create([
            'name' => 'Tecnología',
            'image' => 'https://cdn.pixabay.com/photo/2016/11/29/05/45/apple-1867461_960_720.jpg'
        ]);
        Category::create([
            'name' => 'Deportes',
            'image' => 'https://cdn.pixabay.com/photo/2016/11/29/12/30/action-1866482_960_720.jpg'
        ]);
        Category::create([
            'name' => 'Moda',
            'image' => 'https://cdn.pixabay.com/photo/2016/11/29/09/08/attractive-1865520_960_720.jpg'
        ]);
        Category::create([
            'name' => 'Salud y belleza',
            'image' => 'https://cdn.pixabay.com/photo/2016/11/29/10/07/beautiful-1866488_960_720.jpg'
        ]);
        Category::create([
            'name' => 'Juguetes',
            'image' => 'https://cdn.pixabay.com/photo/2016/11/29/10/07/beautiful-1866488_960_720.jpg'
        ]);
        
    }
}
