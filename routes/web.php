<?php

use Illuminate\Support\Facades\Route;

// Catch-all Route...
Route::get('/', 'HomeController@dashboard')->name('otter.dashboard');