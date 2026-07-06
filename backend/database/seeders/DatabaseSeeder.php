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
        User::firstOrCreate(
            ['email' => 'admin@locomarket.test'],
            [
                'name' => 'Admin LocoMarket',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $legumes = Category::firstOrCreate(['slug' => 'legumes'], ['name' => 'Légumes']);
        $boulangerie = Category::firstOrCreate(['slug' => 'boulangerie'], ['name' => 'Boulangerie']);

        Product::firstOrCreate(
            ['name' => 'Tomates anciennes (1kg)'],
            [
                'category_id' => $legumes->id,
                'description' => 'Tomates du producteur local, récoltées le matin même.',
                'price' => 3.90,
                'stock' => 40,
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Pain de campagne'],
            [
                'category_id' => $boulangerie->id,
                'description' => 'Pain au levain, cuit au feu de bois.',
                'price' => 4.50,
                'stock' => 20,
            ]
        );
    }
}