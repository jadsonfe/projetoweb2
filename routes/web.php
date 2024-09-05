<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\UserController;


// Defina middleware para admin
// Adicione o middleware na definição das rotas
Route::middleware([CheckRole::class.':admin'])->group(function () {
    // Rotas protegidas para admin
    Route::resource('books', BookController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('publishers', PublisherController::class);
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
});
Route::middleware([CheckRole::class.':bibliotecario'])->group(function () {
    // Rotas protegidas para admin
    Route::resource('books', BookController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('publishers', PublisherController::class);
});

Route::get('/', function () {
    return redirect('/books');
});

// Rotas públicas ou que não precisam de autenticação
Route::resource('books', BookController::class)->only(['index', 'show']);
// Rota de login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

// Rota de registro
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Rota de logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

