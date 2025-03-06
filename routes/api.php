<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\UserAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//RUTAS DE AUTENTICACIÓN PUBLICAS
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


// Route::middleware([UserAuth::class])->group(function () {
//     Route::post('/sales', [SalesController::class, 'addSale']);
// });

//RUTAS DE AUTENTICACIÓN PRIVADAS
Route::middleware([UserAuth::class])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::get('me', 'getUser');
    });
    


    //ACCIONES DE VENDEDOR USER
    Route::get('allproducts', [ProductController::class, 'getProducts']);
    Route::get('/products/{id}', [ProductController::class, 'getProductById']);
    Route::post('products', [ProductController::class, 'addProduct']);
    Route::patch('/products/{id}', [ProductController::class, 'updateProductById']);
    

    //ACCIONES DE ADMIN
    Route::middleware([Admin::class])->group(function () {
        Route::controller(ProductController::class)->group(function () {
            Route::post('products', 'addProduct');
            Route::get('/products/{id}', 'getProductById');
            Route::patch('/products/{id}', 'updateProductById');
            Route::delete('/products/{id}', 'deleteProductById');
            
        });
        
        Route::controller(SalesController::class)->group(function () {
            Route::post('/sales', [SalesController::class, 'addSale']);
            Route::get('/sales/report',[SalesController::class, 'report']);
        });
    });
});

