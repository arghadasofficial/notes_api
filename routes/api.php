<?php

use App\Http\Controllers\NotesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ["Server" => "API Server starting..."];
});

Route::post('/note-add', [NotesController::class, 'store']);
Route::get('/notes', [NotesController::class, 'index']);
Route::get('/note-single/{id}', [NotesController::class, 'show']);
Route::post('/note-update/{id}', [NotesController::class, 'update']);
Route::delete('/note-delete/{id}', [NotesController::class, 'destroy']);
Route::get('/search-note', [NotesController::class, 'searchQuery']);
