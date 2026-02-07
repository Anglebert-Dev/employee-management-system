<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reset-password/{token}', function (string $token) {
    return 'Password reset placeholder. Token: '.$token;
})->name('password.reset');
