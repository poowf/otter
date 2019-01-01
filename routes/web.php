<?php

use Illuminate\Support\Facades\Route;

// Catch-all Route...
Route::get('/', 'OtterViewController@dashboard')->name('web.otter.dashboard');
