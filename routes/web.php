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

Route::prefix('profile')
	->middleware(['auth'])
	->group(function () {
		Route::get('/', 'ProfileController@editProfile')->name('profile');
		Route::post('/', 'ProfileController@updateProfile')->name('profile.update');
		Route::get('/password', 'ProfileController@resetPassword')->name('profile.password');
		Route::post('/password', 'ProfileController@updatePassword')->name('profile.reset');
});

Route::prefix('student')
	->middleware(['auth', 'guruMiddleware'])
	->group(function () { 
		Route::get('/', 'StudentController@index')->name('student.index');
		Route::post('/', 'StudentController@store')->name('student.store');
		Route::post('/edit', 'StudentController@update')->name('student.update');
		Route::get('/edit/{student}', 'StudentController@show')->name('student.detail');
		Route::delete('/delete/{student}', 'StudentController@destroy')->name('student.destroy');
		Route::get('/data', 'StudentController@data')->name('student.show');
});

Route::prefix('matpel')
	->middleware(['auth', 'pengurusMiddleware'])
	->group(function() {
		Route::get('/', 'CourseController@index')->name('course.index');
		Route::post('/', 'CourseController@store')->name('course.store');
		Route::get('/edit/{id}', 'CourseController@edit')->name('course.edit');
		Route::post('/edit', 'CourseController@update')->name('course.update');
		Route::delete('/delete/{id}', 'CourseController@destroy')->name('course.destroy');
		Route::get('/data', 'CourseController@data');
});

Route::prefix('akademik')
	->middleware(['auth', 'pengurusMiddleware'])
	->group(function() {
		Route::get('/', 'SemesterController@index')->name('semester.index');
		Route::post('/', 'SemesterController@store')->name('semester.store');
		Route::get('/data', 'SemesterController@data');
});

Route::prefix('kelas')
	->middleware(['auth', 'pengurusMiddleware'])
	->group(function() {
		Route::get('/', 'GroupController@index')->name('kelas.index');
		Route::post('/', 'GroupController@store')->name('kelas.store');
		Route::get('/edit/{id}', 'GroupController@edit')->name('kelas.edit');
		Route::post('/edit', 'GroupController@update')->name('kelas.update');
		Route::delete('/delete/{id}', 'GroupController@destroy')->name('kelas.destroy');
		Route::get('/data', 'GroupController@data');
	});

Route::prefix('guru')
	->middleware(['auth', 'pengurusMiddleware'])
	->group(function() {
		
	});
