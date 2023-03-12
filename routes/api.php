<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


    Route::post('regester',[UserController::class, 'store']);
    Route::post('login',[UserController::class, 'login']);
    Route::post('logout',[UserController::class, 'logout'])->middleware(['auth.guard:user-api']);
    Route::get('profile',[UserController::class, 'profile'])->middleware(['auth.guard:user-api']);
    Route::get('MyProducts',[UserController::class, 'my_products'])->middleware(['auth.guard:user-api']);
    Route::post('image',[UserController::class, 'uploadImage'])->middleware(['auth.guard:user-api']);
   


    
    Route::post('add',[ProductController::class, 'add'])->middleware(['auth.guard:user-api']);
    Route::post('shwo/{id}',[ProductController::class, 'getProductById'])->middleware(['auth.guard:user-api']);
    Route::get('allproduct',[ProductController::class, 'getAllProducts'])->middleware(['auth.guard:user-api']);
    Route::post('edit/{id}',[ProductController::class, 'edit'])->middleware(['auth.guard:user-api']);
    Route::delete('delete/{id}',[ProductController::class, 'destroy'])->middleware(['auth.guard:user-api']);
    Route::get('categoryAndProductsById/{id}',[ProductController::class, 'categoryandproductsbyid']);
    Route::get('Allcategory',[ProductController::class, 'allcategory']);
    Route::get('AllcategoryAndProducts',[ProductController::class, 'Allcategoryandproducts']);
    Route::post('update/{id}',[ProductController::class, 'update'])->middleware(['auth.guard:user-api']);
    Route::post('serch',[ProductController::class, 'serch']);
    Route::post('sort',[ProductController::class, 'sort']);
    Route::post('addcomment/{id}',[ProductController::class, 'addcomment'])->middleware(['auth.guard:user-api']);
    Route::get('allcommentswithproduct/{id}',[ProductController::class, 'allcommentswithproduct']);
    Route::post('addlikeAnddeslike/{id}',[ProductController::class, 'likeAnddeslike'])->middleware(['auth.guard:user-api']);
    Route::get('statusLikeUser/{id}',[ProductController::class, 'getstatususerlike'])->middleware(['auth.guard:user-api']);

Route::get('testonline', function () {
    return "Done";
});

