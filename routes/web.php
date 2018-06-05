<?php

Route::get('/journal/{accessKey}', 'JournalController@view')->name('journal_view');

Route::prefix('/{accessToken}')->middleware(['checkToken'])->group(function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/{userId}', 'HomeController@view')->name('view');
    Route::get('/journal', 'JournalController@index')->name('journal_list');
});


