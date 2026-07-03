<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = $user->role === 'admin'
            ? Order::with('items.product', 'user')
            : Order::with('items.product')->where('user_id', $user->id);

        return $query->latest()->get();
    }

    // POST /api/orders  { items: [{product_id, quantity}, ...] }
    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $order = Order::create([
            'user_id' => $request->user()->id,
            'status' => 'pending',
            'total' => 0,
        ]);

        $total = 0;

        foreach ($data['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);

            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'unit_price' => $product->price,
            ]);

            $total += $product->price * $item['quantity'];
        }

        $order->update(['total' => $total]);

        return response()->json($order->load('items.product'), 201);
    }
}