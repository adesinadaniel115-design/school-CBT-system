<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AdminExportReportController;
use App\Http\Controllers\AdminPerformanceController;
use App\Http\Controllers\AdminStudentController;
use App\Http\Controllers\AdminCenterController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\AdminExamTokenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


// public authentication routes (login, register, password reset)
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.submit');

// password reset URLs outside admin guard
Route::get('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{email}/{token}', [App\Http\Controllers\PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\PasswordResetController::class, 'reset'])->name('password.update');

Route::middleware('auto.login')->group(function () {
    Route::get('/', function () {
        if (auth()->check()) {
            return redirect('/dashboard');
        }

        return redirect('/login');
    });

    // authentication routes are declared above outside this middleware group
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
        // export selected/all history results as PDF
        Route::post('/history/generate', [StudentDashboardController::class, 'generateHistoryPdf'])->name('student.history.generate');
        Route::get('/student/profile', [ProfileController::class, 'editStudent'])->name('student.profile.edit');
        Route::post('/student/profile', [ProfileController::class, 'updateStudent'])->name('student.profile.update');

        Route::post('/exam/start', [ExamController::class, 'startSchool'])->name('exam.start');
        Route::post('/exam/start-jamb', [ExamController::class, 'startJamb'])->name('exam.start.jamb');
        Route::post('/exam/confirm-school', [ExamController::class, 'confirmSchool'])->name('exam.confirm.school');
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
            
            // Center Management
            Route::resource('centers', AdminCenterController::class);
            Route::get('centers/{center}/students', [AdminCenterController::class, 'getStudents'])->name('centers.students');
            
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
            
            // Export Exam Reports
            Route::get('export-reports', [AdminExportReportController::class, 'index'])->name('export-reports.index');
            Route::post('export-reports/generate', [AdminExportReportController::class, 'generate'])->name('export-reports.generate');

            // Performance Analytics
            Route::get('performance', [AdminPerformanceController::class, 'index'])->name('performance.index');
            Route::post('performance/generate', [AdminPerformanceController::class, 'generate'])->name('performance.generate');



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
