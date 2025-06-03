<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Route;

//directorio raiz
Route::get('/', function () {
    return view('/auth/login');
});
//ver las cartas
Route::get('/cards', [CardController::class, 'index'])->name('cards.index');
Route::get('/cards/search', [CardController::class, 'search'])->name('cards.search');
Route::post('/cards/mark-owned/{id}', [CardController::class, 'markOwned'])->name('cards.markOwned');
Route::get('/cards/owned', [CardController::class, 'owned'])->name('cards.owned');
Route::get('/cards/missing', [CardController::class, 'missing'])->name('cards.missing');
Route::delete('/cards/unmark-owned/{cardId}', [CardController::class, 'unmarkOwned'])->middleware('auth');

Route::get('/sobre-mi', function () {
    return view('sobre-mi');
})->name('sobre-mi');


// Para añadir cartas promo a la base de datos (solo admin)
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/promos', [CardController::class, 'ListarPromos'])->name('cards.listarPromos'); // Listar cartas promo
    Route::get('/promos/create', [CardController::class, 'create'])->name('cards.create'); // Formulario de creación
    Route::post('/promos', [CardController::class, 'store'])->name('cards.store'); // Guardar carta
    Route::get('/{card}/edit', [CardController::class, 'edit'])->name('cards.edit'); // Formulario de edición
    Route::put('/promos/{card}', [CardController::class, 'update'])->name('cards.update'); // Actualizar carta
    Route::delete('/promos/{card}', [CardController::class, 'destroy'])->name('cards.destroy'); // Eliminar carta
});


//cosas del breeze 
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
