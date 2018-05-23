<?php

use Illuminate\Support\Facades\Route;

Route::post('download', 'GdprController@download');

Route::get('/show_terms', 'GdprController@showTerms');
Route::post('terms_accepted', [
    'as' => 'terms_accepted',
    'uses' => 'GdprController@termsAccepted',
]);
Route::post('terms_denied', [
    'as' => 'terms_denied',
    'uses' => 'GdprController@termsDenied',
]);
