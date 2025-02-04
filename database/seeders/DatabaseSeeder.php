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
        User::factory()->create([
            'name' => 'manager',
            'email' => 'manager@manager.com',
            'password' => 'manager',
            'role' => '1',
        ]);
        User::factory()->create([
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => 'user',
            'role' => '2',
        ]);

        // Aquí tu código para crear categorías
        Category::create([
            'name' => 'Llar',
            'image' => 'HomeCategory.jpg'
        ]);
        Category::create([
            'name' => 'Tecnologia',
            'image' => 'TecnologyCategory.jpg'
        ]);
        Category::create([
            'name' => 'Esports',
            'image' => 'SportsCategory.jpg'
        ]);
        Category::create([
            'name' => 'Moda',
            'image' => 'ModaCategory.jpg'
        ]);
        Category::create([
            'name' => 'Salut i Bellesa',
            'image' => 'HealthCategory.jpg'
        ]);
        Category::create([
            'name' => 'Joguines',
            'image' => 'ToysCategory.jpg'
        ]);
        Category::create([
            'name' => 'index',
            'image' => '',
            'id' => 7,
        ]);
        
    }
}
