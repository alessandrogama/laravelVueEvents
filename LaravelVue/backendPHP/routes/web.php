<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rota POST para processar a pergunta
Route::post('/perguntar', [IAController::class, 'perguntar']);

