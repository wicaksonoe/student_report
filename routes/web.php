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
	->group(function () {
		Route::get('/', 'CourseController@index')->name('course.index');
		Route::post('/', 'CourseController@store')->name('course.store');
		Route::get('/edit/{id}', 'CourseController@edit')->name('course.edit');
		Route::post('/edit', 'CourseController@update')->name('course.update');
		Route::delete('/delete/{id}', 'CourseController@destroy')->name('course.destroy');
		Route::get('/data', 'CourseController@data');
	});

Route::prefix('akademik')
	->middleware(['auth', 'pengurusMiddleware'])
	->group(function () {
		Route::get('/', 'SemesterController@index')->name('semester.index');
		Route::post('/', 'SemesterController@store')->name('semester.store');
		Route::get('/data', 'SemesterController@data');
	});

Route::prefix('kelas')
	->middleware(['auth', 'pengurusMiddleware'])
	->group(function () {
		Route::get('/', 'GroupController@index')->name('kelas.index');
		Route::post('/', 'GroupController@store')->name('kelas.store');
		Route::get('/edit/{id}', 'GroupController@edit')->name('kelas.edit');
		Route::post('/edit', 'GroupController@update')->name('kelas.update');
		Route::delete('/delete/{id}', 'GroupController@destroy')->name('kelas.destroy');
		Route::get('/data', 'GroupController@data');
	});

Route::prefix('guru')
	->middleware(['auth', 'pengurusMiddleware'])
	->group(function () {
		Route::get('/', 'TeacherController@index')->name('teacher.index');
		Route::post('/', 'TeacherController@store')->name('teacher.store');
		Route::get('/edit/{method}/{id}', 'TeacherController@edit')->name('teacher.edit');
		Route::delete('/delete/{id}', 'TeacherController@destroy')->name('teacher.destroy');
		Route::get('/data/{method}', 'TeacherController@data');
		Route::get('/message/{method}/{id}', 'TeacherController@message');
	});

Route::prefix('jadwal')
	->middleware(['auth', 'pengurusMiddleware'])
	->group(function () {
		Route::prefix('jam')->group(function () {
			Route::get('/', 'LessonHourController@index')->name('jadwal.jam.index');
			Route::post('/', 'LessonHourController@store')->name('jadwal.jam.store');
			Route::delete('/{id}', 'LessonHourController@destroy')->name('jadwal.jam.destroy');
			Route::get('/data', 'LessonHourController@data');
		});
		Route::prefix('kelas')->group(function () {
			Route::get('/', 'ScheduleController@index')->name('jadwal.kelas.index');
			Route::post('/', 'ScheduleController@store')->name('jadwal.kelas.store');
			Route::get('/edit/{id}', 'ScheduleController@edit')->name('jadwal.kelas.edit');
			Route::post('/edit', 'ScheduleController@update')->name('jadwal.kelas.update');
			Route::delete('/delete/{id}', 'ScheduleController@destroy')->name('jadwal.kelas.destroy');
			Route::get('/data', 'ScheduleController@data');
			Route::get('/data/matpel', 'ScheduleController@data_guru');
		});
	});

Route::prefix('pertemuan')
	->middleware(['auth', 'guruMiddleware'])
	->group(function () {
		Route::get('/', 'AttendanceReportController@index')->name('pertemuan.index');
		Route::post('/', 'AttendanceReportController@store')->name('pertemuan.store');
		Route::get('/show', 'AttendanceReportController@show')->name('pertemuan.show');
		Route::post('/edit', 'AttendanceReportController@update')->name('pertemuan.update');
		Route::get('/data/matpel', 'AttendanceReportController@data_matpel');
		Route::get('/data/notulensi/{schedule_id}', 'AttendanceReportController@data_notulensi');
		Route::get('/message/notulensi/{schedule_id}', 'AttendanceReportController@message');
	});

Route::prefix('nilai')
	->middleware(['auth', 'guruMiddleware'])
	->group(function() {
		Route::get('/', 'ReportController@index')->name('nilai.index');
		Route::post('/', 'ReportController@store')->name('nilai.store');
		Route::post('/cetak', 'ReportController@print')->name('nilai.print');
		Route::post('/update', 'ReportController@update')->name('nilai.update');
		Route::get('/data/siswa', 'ReportController@data_siswa');
		Route::get('/data/raport', 'ReportController@data_nilai');
	});
