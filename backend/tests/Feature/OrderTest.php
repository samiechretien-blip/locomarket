<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_an_order(): void
    {
        $response = $this->postJson('/api/orders', ['items' => []]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_an_order_with_correct_total(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $category = Category::create(['name' => 'Légumes', 'slug' => 'legumes']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Tomates',
            'price' => 3.50,
            'stock' => 20,
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/orders', [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ]);

        $response->assertStatus(201)
           ->assertJsonPath('total', 7);

        $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 2]);
    }

    public function test_client_only_sees_their_own_orders(): void
    {
        $userA = User::factory()->create(['role' => 'client']);
        $userB = User::factory()->create(['role' => 'client']);
        $category = Category::create(['name' => 'Légumes', 'slug' => 'legumes']);
        $product = Product::create(['category_id' => $category->id, 'name' => 'Carottes', 'price' => 2, 'stock' => 10]);

        $this->actingAs($userA, 'sanctum')->postJson('/api/orders', [
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ]);

        $response = $this->actingAs($userB, 'sanctum')->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonCount(0);
    }
}