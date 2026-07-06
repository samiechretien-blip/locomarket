<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public (catalogue)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// Authentifié (client ou admin)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);

    // Admin uniquement
    Route::middleware('is_admin')->group(function () {
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    });
});
// ROUTE TEMPORAIRE - à supprimer après usage
Route::delete('/temp-cleanup/{product}', function (\App\Models\Product $product, \Illuminate\Http\Request $request) {
    if ($request->query('key') !== 'nettoyage2026') {
        abort(403);
    }
    $product->items()->delete(); // supprime les order_items liés
    $product->delete();
    return response()->json(['message' => 'Produit et ses commandes associées supprimés.']);
});