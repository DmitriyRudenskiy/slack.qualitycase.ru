<?php

Route::prefix('/{accessToken}')->middleware(['checkToken'])->group(function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/{userId}', 'HomeController@view')->name('view');
});