<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_list_products(): void
    {
        $category = Category::create(['name' => 'Légumes', 'slug' => 'legumes']);
        Product::create(['category_id' => $category->id, 'name' => 'Carottes', 'price' => 2, 'stock' => 10]);
        Product::create(['category_id' => $category->id, 'name' => 'Poireaux', 'price' => 3, 'stock' => 5]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_only_admin_can_create_a_product(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $category = Category::create(['name' => 'Légumes', 'slug' => 'legumes']);

        $response = $this->actingAs($client, 'sanctum')->postJson('/api/products', [
            'category_id' => $category->id,
            'name' => 'Produit test',
            'price' => 5,
            'stock' => 10,
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_create_a_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Boulangerie', 'slug' => 'boulangerie']);

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/products', [
            'category_id' => $category->id,
            'name' => 'Baguette',
            'price' => 1.2,
            'stock' => 20,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('name', 'Baguette');

        $this->assertDatabaseHas('products', ['name' => 'Baguette']);
    }
}