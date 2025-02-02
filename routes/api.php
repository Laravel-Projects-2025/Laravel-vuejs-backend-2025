<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\Frontend\WelcomeController::class);
Route::get('/posts/{post:slug}', \App\Http\Controllers\Frontend\PostShowController::class);

Route::apiResource('/dashboard/posts', \App\Http\Controllers\Backend\PostController::class)
    ->middleware(['auth:sanctum'])
    ->except(['create', 'edit']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', function (Request $request) {
    return User::all();
});

Route::get('/403', function (Request $request) {
    return response()->json('unauthorised',403);
});
