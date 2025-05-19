<?php

use App\Http\Controllers\DaftarController;
use App\Http\Controllers\placeController;
use App\Http\Controllers\PrintController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
// home
Route::get('/', [DaftarController::class, 'index'])->name('header.index');
// USERS
// get users
Route::get('/users', [UserController::class, 'index'])->name('users.index');
// create - form
Route::get('/user/create/kantor', [UserController::class, 'create'])->name('user.createKantor');
Route::get('/user/create/awak12', [UserController::class, 'createAwak12'])->name('user.createAwak12');
// store
Route::post('/user/create/kantor', [UserController::class, 'store'])->name('user.store');
Route::post('/user/create/awak12', [UserController::class, 'storeAwak12'])->name('user.storeAwak12');
// edit
Route::get('/awak12/edit/user/{user}', [UserController::class, 'editPageAwak12'])->name('edit.awak12');
Route::get('/kantor/edit/user/{user}', [UserController::class, 'editPageKantor'])->name('edit.kantor');
// update
Route::put('/awak12/update/user/{userId}', [UserController::class, 'updateAwak12'])->name('update.awak12');
Route::put('/kantor/update/user/{userId}', [UserController::class, 'updateKantor'])->name('update.kantor');
// delete
Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
// filter
Route::get('/users/filter', [UserController::class, 'filterUsers'])->name('users.filter');

Route::get('/form', [UserController::class, 'formUser'])->name('user.form');
// print
Route::get('/print', [UserController::class, 'printData'])->name('users.print');
// print filter
Route::get('/print/filter', [UserController::class, 'filterUsersPrint'])->name('users.print.filter');
// search
Route::get('/search', [UserController::class, 'searchUser'])->name('user.search');
