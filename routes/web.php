<?php

use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    return auth()->user()->portable();
});
