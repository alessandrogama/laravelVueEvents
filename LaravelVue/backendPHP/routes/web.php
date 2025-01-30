<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/perguntar-form', function () {
    return view('perguntar'); // Você precisaria criar uma view simples com um formulário
});

// Rota POST para processar a pergunta
Route::post('/perguntar', [IAController::class, 'perguntar']);