<?php

use Illuminate\Support\Facades\Route;

// Catch-all Route...
Route::get('/{view?}', 'HomeController@dashboard')->where('view', '(.*)')->name('otter.dashboard');