<?php

use Illuminate\Support\Facades\Route;

Route::get('/','HomeController@HomeIndex');
Route::post('/contactSend','HomeController@ContactSend');

Route::get('/Courses','CoursesController@CoursePage');
Route::get('/Policy','PolicyController@PolicyPage');
Route::get('/Projects','ProjectsController@ProjectPage');
Route::get('/Terms','TermsController@TermPage');
Route::get('/Contact','ContactController@ContactPage');
