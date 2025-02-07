<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IAController;
use Illuminate\Http\Middleware\HandleCors;

Route::middleware('api')->post('/perguntar', [IAController::class, 'perguntar']);

Route::middleware('api')->get('/test', function () {
    dd('Rota está funcionando!');
});
