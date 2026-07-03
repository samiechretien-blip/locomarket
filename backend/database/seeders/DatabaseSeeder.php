<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin LocoMarket',
            'email' => 'admin@locomarket.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $legumes = Category::create(['name' => 'Légumes', 'slug' => 'legumes']);
        $boulangerie = Category::create(['name' => 'Boulangerie', 'slug' => 'boulangerie']);

        Product::create([
            'category_id' => $legumes->id,
            'name' => 'Tomates anciennes (1kg)',
            'description' => 'Tomates du producteur local, récoltées le matin même.',
            'price' => 3.90,
            'stock' => 40,
        ]);

        Product::create([
            'category_id' => $boulangerie->id,
            'name' => 'Pain de campagne',
            'description' => 'Pain au levain, cuit au feu de bois.',
            'price' => 4.50,
            'stock' => 20,
        ]);
    }
}