<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SimplePagesController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [SimplePagesController::class, 'index']);

Route::get('/profile', [ProfileController::class, 'home']);
Route::get('/api-settings', [ProfileController::class, 'api_settings']);
Route::get('/my-orders', [ProfileController::class, 'my_orders']);
Route::get('/get-user-orders', [ProfileController::class, 'get_user_orders']);
Route::post('/create-order', [ProfileController::class, 'create_order']);
Route::post('/delete-order', [ProfileController::class, 'delete_order']);

Route::get('/test', [TestController::class, 'index']);

Route::get('/register', [UserController::class, 'create']);
Route::get('/login', [UserController::class, 'login']);
Route::post('/register-user', [UserController::class, 'register_user']);
Route::post('/login-user', [UserController::class, 'login_user']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/save-api-credentials', [UserController::class, 'save_api_credentials']);