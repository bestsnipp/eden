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

Route::get('/', [Dgharami\Eden\RouteController::class, 'entry'])->name('eden.entry');

Route::get('/{resource}', [Dgharami\Eden\RouteController::class, 'index'])->name('eden.page');
Route::get('/{resource}/create', [Dgharami\Eden\RouteController::class, 'create'])->name('eden.create');
Route::get('/{resource}/{resourceId}', [Dgharami\Eden\RouteController::class, 'show'])->name('eden.show');
Route::get('/{resource}/{resourceId}/edit', [Dgharami\Eden\RouteController::class, 'edit'])->name('eden.edit');
