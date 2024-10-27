<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AssessmentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth:web'])->group(function () {
    Route::get('/teacher/create-class', [TeacherController::class, 'createClassForm'])->name('teacher.createClassForm');
    Route::post('/teacher/create-class', [TeacherController::class, 'createClass'])->name('teacher.createClass');
    Route::get('/teacher/create-student', [TeacherController::class, 'createStudentForm'])->name('teacher.createStudentForm');
    Route::post('/teacher/create-student', [TeacherController::class, 'createStudent'])->name('teacher.createStudent');
    Route::get('/teacher/create-assessment', [TeacherController::class, 'createAssessmentForm'])->name('teacher.createAssessmentForm');
    Route::post('/teacher/create-assessment', [TeacherController::class, 'createAssessment'])->name('teacher.createAssessment');
    Route::get('/assessments', [TeacherController::class, 'showScheduledAssessments'])->name('assessments.scheduled');
    Route::get('/assessments/{assessment}/schedule', [TeacherController::class, 'showGenerateSlotsForm'])
    ->name('assessments.generate_slots_form');
    Route::post('/assessments/{assessment}/generate-slots', [TeacherController::class, 'generateSlots'])
    ->name('assessments.generateSlots');

    Route::get('/assessments/{assessment}/assign', [AssessmentController::class, 'showAssignStudentsForm'])
    ->name('assessments.assign_students_form');
    Route::post('/assessments/{assessment}/slots', [AssessmentController::class, 'getSlotsForDate'])->name('assessments.getSlotsForDate');
    Route::get('/assessments/{assessment}/students', [AssessmentController::class, 'getStudentsForClass'])->name('assessments.getStudentsForClass');
    Route::post('/assessments/saveAssignments', [AssessmentController::class, 'saveAssignments'])->name('assessments.saveAssignments');
    Route::get('/assessments/{assessment}/slots', [AssessmentController::class, 'listSlots'])->name('assessments.listSlots');
    Route::get('/assessments/slot/{slot}', [AssessmentController::class, 'viewSlotStudents'])->name('assessments.viewSlotStudents');
    Route::post('/assessments/{assessment}/auto-assign', [AssessmentController::class, 'autoAssignStudentsToSlots'])->name('assessments.autoAssignStudents');

});


require __DIR__.'/auth.php';
