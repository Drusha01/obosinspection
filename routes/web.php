<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Test;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test',Test::class)->name('test');