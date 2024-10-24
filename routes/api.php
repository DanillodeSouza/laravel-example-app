<?php

use App\Http\Controllers\UsersController;
use App\Http\Controllers\Transactions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users/', [UsersController::class, 'list']);
Route::get('/users/{param}', [UsersController::class, 'show']);
Route::post('/users/', [UsersController::class, 'store']);


Route::get('/transactions/', [Transactions::class, 'list']);
Route::get('/transactions/{param}', [Transactions::class, 'show']);
Route::post('/transactions/', [Transactions::class, 'store']);