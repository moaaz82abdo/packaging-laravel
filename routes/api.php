<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::post('/register', [RegisteredUserController::class, 'store']);
// Route::post('/login', function (Request $request) {
//     $user = User::where('email', $request->email)->first();

//     if (!$user || !Hash::check($request->password, $user->password)) {
//         return response()->json(['message' => 'Invalid credentials'], 401);
//     }

   
//     $token = $user->createToken('my-token')->plainTextToken;

    
//     return response()->json(['token' => $token]);
// });
// Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');

// Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
  
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth',Admin::class])->group(function () {
});
Route::apiResource('/orders', OrderController::class)->middleware('auth:sanctum');

// Orders
// Route::get('/orders', [OrderController::class, 'index']);
// Route::post('/orders', [OrderController::class, 'store']);
// Route::get('/orders/{id}', [OrderController::class, 'show']);
// Route::get('/orders/edit/{id}', [OrderController::class, 'edit']);
// Route::put('/orders/{id}', [OrderController::class, 'update']);
// Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

// Items
Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/{id}', [ItemController::class, 'show']);
Route::post('/items', [ItemController::class, 'store'])->middleware(['auth:sanctum',Admin::class]);
Route::get('/items/edit/{id}', [ItemController::class, 'edit']);
Route::put('/items/{id}', [ItemController::class, 'update']);
Route::delete('/items/{id}', [ItemController::class, 'destroy']);

// Logs
Route::get('/logs', [LogController::class, 'index']);





require __DIR__.'/auth.php';