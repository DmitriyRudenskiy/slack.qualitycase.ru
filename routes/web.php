<?php

Route::get('/journal/{accessKey}', 'JournalController@view')->name('journal_view');

Route::prefix('/{accessToken}')->middleware(['checkToken'])->group(function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::post('/journal/rating', 'JournalController@rating')->name('journal_rating');
    Route::get('/journal', 'JournalController@index')->name('journal_list');
    Route::get('/{userId}', 'HomeController@view')->name('view');

});


