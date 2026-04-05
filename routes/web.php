<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Auth::routes(['register' => false]);

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', App\Http\Controllers\UserController::class)->except(['show']);
    Route::resource('faculties', App\Http\Controllers\FacultyController::class)->except(['show']);
    Route::resource('rooms', App\Http\Controllers\RoomController::class)->except(['show']);
    Route::resource('courses', App\Http\Controllers\CourseController::class)->except(['show']);
    Route::resource('sets', App\Http\Controllers\SetController::class)->except(['show']);
    Route::resource('subjects', App\Http\Controllers\SubjectController::class)->except(['show']);
    Route::resource('schedules', App\Http\Controllers\ScheduleController::class)->except(['show']);

    Route::prefix('timetables')->name('timetables.')->group(function () {
        Route::get('/', [App\Http\Controllers\TimetableController::class, 'index'])->name('index');
        Route::get('faculty/{faculty}', [App\Http\Controllers\TimetableController::class, 'faculty'])->name('faculty');
        Route::get('room/{room}', [App\Http\Controllers\TimetableController::class, 'room'])->name('room');
        Route::get('course/{course}', [App\Http\Controllers\TimetableController::class, 'course'])->name('course');
        Route::get('course/{course}/set/{set}', [App\Http\Controllers\TimetableController::class, 'courseSet'])->name('course.set');
        Route::get('set/{set}', [App\Http\Controllers\TimetableController::class, 'set'])->name('set');
        Route::get('online', [App\Http\Controllers\TimetableController::class, 'online'])->name('online');
    });

    Route::prefix('exports')->name('exports.')->group(function () {
        Route::get('faculty/{faculty}/pdf', [App\Http\Controllers\ExportController::class, 'facultyPdf'])->name('faculty.pdf');
        Route::get('room/{room}/pdf', [App\Http\Controllers\ExportController::class, 'roomPdf'])->name('room.pdf');
        Route::get('course/{course}/pdf', [App\Http\Controllers\ExportController::class, 'coursePdf'])->name('course.pdf');
        Route::get('course/{course}/set/{set}/pdf', [App\Http\Controllers\ExportController::class, 'courseSetPdf'])->name('course.set.pdf');
        Route::get('set/{set}/pdf', [App\Http\Controllers\ExportController::class, 'setPdf'])->name('set.pdf');
        Route::get('batch/faculty', [App\Http\Controllers\ExportController::class, 'batchFaculty'])->name('batch.faculty');
        Route::get('batch/course', [App\Http\Controllers\ExportController::class, 'batchCourse'])->name('batch.course');
        Route::get('batch/room', [App\Http\Controllers\ExportController::class, 'batchRoom'])->name('batch.room');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('faculty-load', [App\Http\Controllers\ReportController::class, 'facultyLoad'])->name('faculty-load');
        Route::get('conflicts', [App\Http\Controllers\ReportController::class, 'conflictReport'])->name('conflicts');
        Route::get('room-utilization', [App\Http\Controllers\ReportController::class, 'roomUtilization'])->name('room-utilization');
        Route::get('batch-export', [App\Http\Controllers\ReportController::class, 'batchExport'])->name('batch-export');
    });
});
