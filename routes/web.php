<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Listing;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Listings
Route::get('/', [ListingController::class, 'index']); //all listings
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth'); //create form;
Route::post('/listings', [ListingController::class, 'store'])->middleware('auth'); //store listing data;
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth'); //manage listings
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth'); //show edit form;
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth'); //edit to update;
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth'); //delete;
Route::get('/listings/{listing}', [ListingController::class, 'show']); //get single listing;

//User
Route::get('/register', [UserController::class, 'create'])->middleware('guest'); //Show Register/Create Form;
Route::post('/users', [UserController::class, 'store']); //store the user;
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth'); //logout user;
Route::get('/login', [UserController::class, 'show'])->name('login')->middleware('guest'); //show login
Route::post('/users/authenticate', [UserController::class, 'authenticate']); //login

