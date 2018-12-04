<?php

use Illuminate\Support\Facades\Route;

// Catch-all Route...
Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('otter.index');