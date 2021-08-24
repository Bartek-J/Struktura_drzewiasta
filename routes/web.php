<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', [App\Http\Controllers\TreeController::class, 'index'])->name('drzewo');
Route::get('/create', [App\Http\Controllers\TreeController::class, 'add'])->name('createTree')->middleware('admin');
Route::delete('/delete', [App\Http\Controllers\TreeController::class, 'delete'])->name('deleteTree')->middleware('admin');
Route::patch('/edit', [App\Http\Controllers\TreeController::class, 'changeName'])->name('editTree')->middleware('admin');
Route::patch('/move', [App\Http\Controllers\TreeController::class, 'move'])->name('moveTree')->middleware('admin');

Auth::routes();

