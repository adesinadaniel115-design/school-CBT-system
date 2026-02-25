<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AdminStudentController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\AdminExamTokenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auto.login')->group(function () {
    Route::get('/', function () {
        if (auth()->check()) {
            return redirect('/dashboard');
        }

        return redirect('/login');
    });

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () {
            if (auth()->user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('student.dashboard');
        })->name('dashboard');

        // Student Routes
        Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
        Route::get('/history', [StudentDashboardController::class, 'history'])->name('student.history');
        Route::post('/history/clear', [StudentDashboardController::class, 'clearHistory'])->name('student.history.clear');
        Route::get('/student/profile', [ProfileController::class, 'editStudent'])->name('student.profile.edit');
        Route::post('/student/profile', [ProfileController::class, 'updateStudent'])->name('student.profile.update');

        Route::post('/exam/start', [ExamController::class, 'start'])->name('exam.start');
        Route::post('/exam/start-jamb', [ExamController::class, 'startJamb'])->name('exam.start.jamb');
        Route::post('/exam/confirm', [ExamController::class, 'confirmJamb'])->name('exam.confirm.jamb');
        Route::post('/exam/validate-token', [ExamController::class, 'validateToken'])->name('exam.validate.token');
        Route::get('/exam/{session}', [ExamController::class, 'take'])->name('exam.take');
        Route::post('/exam/{session}/answer', [ExamController::class, 'saveAnswer'])->name('exam.answer');
        Route::post('/exam/{session}/terminate', [ExamController::class, 'terminate'])->name('exam.terminate');
        Route::post('/exam/{session}/submit', [ExamController::class, 'submit'])->name('exam.submit');
        Route::get('/exam/{session}/result', [ExamController::class, 'result'])->name('exam.result');
        Route::get('/exam/{session}/review', [ExamController::class, 'review'])->name('exam.review');
    });

    Route::prefix('admin')
        ->middleware(['auth', 'admin'])
        ->name('admin.')
        ->group(function () {
            Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('all-questions', [AdminDashboardController::class, 'allQuestions'])->name('all-questions');
            Route::get('all-exams', [AdminDashboardController::class, 'allExams'])->name('all-exams');
            
            // Student Management
            Route::resource('students', AdminStudentController::class);
            
            // Exam Token Management
            Route::get('tokens', [AdminExamTokenController::class, 'index'])->name('tokens.index');
            Route::get('tokens/create', [AdminExamTokenController::class, 'create'])->name('tokens.create');
            Route::post('tokens', [AdminExamTokenController::class, 'store'])->name('tokens.store');
            Route::post('tokens/{token}/toggle', [AdminExamTokenController::class, 'toggle'])->name('tokens.toggle');
            Route::delete('tokens/{token}', [AdminExamTokenController::class, 'destroy'])->name('tokens.destroy');
            Route::delete('tokens-bulk-delete', [AdminExamTokenController::class, 'bulkDelete'])->name('tokens.bulk-delete');
            Route::get('tokens/print', [AdminExamTokenController::class, 'print'])->name('tokens.print');
            Route::post('tokens/validate', [AdminExamTokenController::class, 'validate'])->name('tokens.validate');
            
            // Subject & Question Management
            Route::resource('subjects', SubjectController::class);
            Route::resource('questions', QuestionController::class)->except(['show']);
            Route::get('questions-import/download-template', [QuestionController::class, 'downloadTemplate'])->name('questions.import.template');
            Route::get('questions-import', [QuestionController::class, 'showImportForm'])->name('questions.import.form');
            Route::post('questions-import', [QuestionController::class, 'import'])->name('questions.import');
            
            // Reports & Analytics
            Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
            Route::get('reports/{session}', [AdminReportController::class, 'show'])->name('reports.show');
            
            // Settings
            Route::get('settings', [AdminSettingsController::class, 'index'])->name('settings.index');
            Route::post('settings', [AdminSettingsController::class, 'update'])->name('settings.update');
            Route::post('settings/clear-exam-sessions', [AdminSettingsController::class, 'clearExamSessions'])->name('settings.clear-exam-sessions');
            Route::post('settings/delete-exam-sessions', [AdminSettingsController::class, 'hardDeleteExamSessions'])->name('settings.delete-exam-sessions');

            // Profile
            Route::get('profile', [ProfileController::class, 'editAdmin'])->name('profile.edit');
            Route::post('profile', [ProfileController::class, 'updateAdmin'])->name('profile.update');
        });
});
