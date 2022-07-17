<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Customer', 'as' => 'customer.'], function() {

    // Get directions calculation
    Route::get('directions', 'OrderController@getDirections');

});
