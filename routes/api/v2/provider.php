<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Provider', 'as' => 'provider.'], function() {

    // Set driver provider status
    Route::post('update-live-status', 'ActiveStatusLocationController@updateStatus');
    // Get driver provider status
    Route::get('live-status', 'ActiveStatusLocationController@updateLocation');

    // Set driver provider location
    Route::post('update-live-location', 'ActiveStatusLocationController@updateLocation');
    // Get driver provider location
    Route::get('live-location', 'ActiveStatusLocationController@getLocation');

});
