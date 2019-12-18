<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::group(['prefix' => 'profile'], function () {
	Route::get('/', 'ProfileController@editProfile')->name('profile');
	Route::post('/', 'ProfileController@updateProfile')->name('profile.update');
});

Route::group(['prefix' => 'student'], function () { 
	Route::get('/', 'StudentController@index')->name('student.index');
	Route::post('/', 'StudentController@store')->name('student.store');
	Route::post('/update', 'StudentController@update')->name('student.update');
	Route::get('/all', 'StudentController@showAll')->name('student.show');
	Route::get('/{student}', 'StudentController@show')->name('student.detail');
	Route::delete('/{student}', 'StudentController@destroy')->name('student.destroy');
});
// Route::resource('student', 'StudentController');
