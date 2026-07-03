<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        return $query->paginate(12);
    }

    public function show(Product $product)
    {
        return $product->load('category');
    }
public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        return response()->json($product, 201);
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return response()->json($product);
    }
    public function destroy(Product $product)
{
    try {
        $product->delete();
    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json([
            'message' => 'Impossible de supprimer ce produit : il est référencé dans une ou plusieurs commandes existantes.',
        ], 409);
    }

    return response()->json(null, 204);
}
}