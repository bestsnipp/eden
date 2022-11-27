<?php

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

Route::middleware(['web', 'auth', config('jetstream.auth_session'), 'verified'])
    ->prefix(config('eden.entry'))
    ->group(function () {

    Route::get('/', [Dgharami\Eden\RouteController::class, 'entry'])->name('eden.entry');

    Route::get('/{slug}', [Dgharami\Eden\RouteController::class, 'index'])->name('eden.page');
    Route::get('/{slug}/create', [Dgharami\Eden\RouteController::class, 'create'])->name('eden.create');
    Route::get('/{slug}/{id}', [Dgharami\Eden\RouteController::class, 'show'])->name('eden.show');
    Route::get('/{slug}/{id}/edit', [Dgharami\Eden\RouteController::class, 'edit'])->name('eden.edit');

});
