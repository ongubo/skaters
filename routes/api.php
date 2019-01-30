<?php

// API routes
Route::post('/users/create', 'APIController@store_user');
Route::post('/tricks/create', 'APIController@store_tricks');
Route::get('/tricks', 'APIController@tricks_index');
Route::post('/tricks/favourite', 'APIController@tricks_favourite');
