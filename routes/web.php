<?php

// ============================================================
// Admin\Master Controllers
// ============================================================
use App\Http\Controllers\Admin\Master\FacultyController;
use App\Http\Controllers\Admin\Master\StudyProgramController;
use App\Http\Controllers\Admin\Master\UserController;

// ============================================================
// Admin\University Controllers
// ============================================================
use App\Http\Controllers\Admin\University\AccreditationAgencyController as UniversityAccreditationAgencyController;
use App\Http\Controllers\Admin\University\AccreditationBatchController as UniversityAccreditationBatchController;
use App\Http\Controllers\Admin\University\AccreditationController as UniversityAccreditationController;
use App\Http\Controllers\Admin\University\AccreditationIndicatorController as UniversityAccreditationIndicatorController;
use App\Http\Controllers\Admin\University\AccreditationInputController as UniversityAccreditationInputController;
use App\Http\Controllers\Admin\University\DashboardController as UniversityDashboardController;
use App\Http\Controllers\Admin\University\IndicatorTemplateController as UniversityIndicatorTemplateController;

// ============================================================
// Admin\StudyProgram Controllers
// ============================================================
use App\Http\Controllers\Admin\StudyProgram\AccreditationAgencyController as StudyProgramAccreditationAgencyController;
use App\Http\Controllers\Admin\StudyProgram\AccreditationBatchController as StudyProgramAccreditationBatchController;
use App\Http\Controllers\Admin\StudyProgram\AccreditationController as StudyProgramAccreditationController;
use App\Http\Controllers\Admin\StudyProgram\AccreditationIndicatorController as StudyProgramAccreditationIndicatorController;
use App\Http\Controllers\Admin\StudyProgram\AccreditationInputController as StudyProgramAccreditationInputController;
use App\Http\Controllers\Admin\StudyProgram\DashboardController as StudyProgramDashboardController;
use App\Http\Controllers\Admin\StudyProgram\IndicatorTemplateController as StudyProgramIndicatorTemplateController;

// ============================================================
// Operator Controllers
// ============================================================
use App\Http\Controllers\Operator\AccreditationController as OperatorAccreditationController;
use App\Http\Controllers\Operator\AccreditationIndicatorController as OperatorAccreditationIndicatorController;
use App\Http\Controllers\Operator\AccreditationInputController as OperatorAccreditationInputController;
use App\Http\Controllers\Operator\DashboardOperatorController;


// ============================================================
// Other
// ============================================================
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    Route::get('profile', [ProfileController::class, 'edit'])
        ->name('profiles.edit');

    Route::put('profile', [ProfileController::class, 'update'])
        ->name('profiles.update');

});

// ============================================================
// Admin Dashboard Mode Switch
// ============================================================
Route::post('dashboard-admin/switch-mode', function (\Illuminate\Http\Request $request) {
    $mode = $request->input('mode');
    if (!in_array($mode, ['university', 'study-program'])) {
        return redirect()->back();
    }

    session(['admin_dashboard_mode' => $mode]);

    $currentUrl = url()->previous();
    $prefix     = $mode === 'university' ? 'university' : 'study-program';

    // Peta segment URL ke nama route equivalen
    $segmentMap = [
        'accreditation-agencies' => "dashboard-admin.{$prefix}.accreditation-agencies.index",
        'accreditation-batches'  => "dashboard-admin.{$prefix}.accreditation-batches.index",
        'accreditations'         => "dashboard-admin.{$prefix}.accreditations.index",
    ];

    foreach ($segmentMap as $segment => $routeName) {
        if (str_contains($currentUrl, $segment)) {
            return redirect()->route($routeName);
        }
    }

    // Default: dashboard mode tujuan
    return redirect()->route("dashboard-admin.{$prefix}.index");
})->middleware(['auth', 'role:admin'])->name('dashboard-admin.switch-mode');

// ============================================================
// Dashboard Admin
// ============================================================
Route::prefix('dashboard-admin')->name('dashboard-admin.')->middleware(['role:admin'])->group(function () {

    // --------------------------------------------------------
    // Master
    // --------------------------------------------------------
    Route::prefix('master')->name('master.')->group(function () {

        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        Route::resource('faculties', FacultyController::class);

        Route::resource('study-programs', StudyProgramController::class);
    });

    // --------------------------------------------------------
    // University
    // --------------------------------------------------------
    Route::prefix('university')->name('university.')->group(function () {

        Route::get('/', [UniversityDashboardController::class, 'index'])->name('index');

        Route::resource('accreditation-agencies', UniversityAccreditationAgencyController::class);

        Route::prefix('accreditation-agencies/{accreditationAgency}')->name('accreditation-agencies.')->group(function () {
            Route::get('indicator-templates', [UniversityIndicatorTemplateController::class, 'index'])
                ->name('indicator-templates.index');
            Route::get('indicator-templates/create', [UniversityIndicatorTemplateController::class, 'create'])
                ->name('indicator-templates.create');
            Route::post('indicator-templates', [UniversityIndicatorTemplateController::class, 'store'])
                ->name('indicator-templates.store');
            Route::get('indicator-templates/{indicatorTemplate}/edit', [UniversityIndicatorTemplateController::class, 'edit'])
                ->name('indicator-templates.edit');
            Route::put('indicator-templates/{indicatorTemplate}', [UniversityIndicatorTemplateController::class, 'update'])
                ->name('indicator-templates.update');
            Route::delete('indicator-templates/{indicatorTemplate}', [UniversityIndicatorTemplateController::class, 'destroy'])
                ->name('indicator-templates.destroy');
        });

        Route::resource('accreditation-batches', UniversityAccreditationBatchController::class);

        Route::resource('accreditations', UniversityAccreditationController::class);
        Route::get('accreditations/{accreditation}/preview', [UniversityAccreditationController::class, 'preview'])
            ->name('accreditations.preview');
        Route::patch('accreditations/{accreditation}/complete', [UniversityAccreditationController::class, 'toggleComplete'])
            ->name('accreditations.complete');

        Route::prefix('accreditations/{accreditation}')->name('accreditations.')->group(function () {
            Route::get('indicators', [UniversityAccreditationIndicatorController::class, 'index'])
                ->name('indicators.index');
            Route::post('indicators/import', [UniversityAccreditationIndicatorController::class, 'import'])
                ->name('indicators.import');
            Route::get('indicators/create', [UniversityAccreditationIndicatorController::class, 'create'])
                ->name('indicators.create');
            Route::post('indicators', [UniversityAccreditationIndicatorController::class, 'store'])
                ->name('indicators.store');
            Route::delete('indicators/{indicator}', [UniversityAccreditationIndicatorController::class, 'destroy'])
                ->name('indicators.destroy');

            Route::get('input', [UniversityAccreditationInputController::class, 'index'])
                ->name('input.index');
            Route::get('input/{indicator}', [UniversityAccreditationInputController::class, 'edit'])
                ->name('input.edit');
            Route::put('input/{indicator}', [UniversityAccreditationInputController::class, 'update'])
                ->name('input.update');
        });
    });

    // --------------------------------------------------------
    // Study Program
    // --------------------------------------------------------
    Route::prefix('study-program')->name('study-program.')->group(function () {

        Route::get('/', [StudyProgramDashboardController::class, 'index'])->name('index');

        Route::resource('accreditation-agencies', StudyProgramAccreditationAgencyController::class);

        Route::prefix('accreditation-agencies/{accreditationAgency}')->name('accreditation-agencies.')->group(function () {
            Route::get('indicator-templates', [StudyProgramIndicatorTemplateController::class, 'index'])
                ->name('indicator-templates.index');
            Route::get('indicator-templates/create', [StudyProgramIndicatorTemplateController::class, 'create'])
                ->name('indicator-templates.create');
            Route::post('indicator-templates', [StudyProgramIndicatorTemplateController::class, 'store'])
                ->name('indicator-templates.store');
            Route::get('indicator-templates/{indicatorTemplate}/edit', [StudyProgramIndicatorTemplateController::class, 'edit'])
                ->name('indicator-templates.edit');
            Route::put('indicator-templates/{indicatorTemplate}', [StudyProgramIndicatorTemplateController::class, 'update'])
                ->name('indicator-templates.update');
            Route::delete('indicator-templates/{indicatorTemplate}', [StudyProgramIndicatorTemplateController::class, 'destroy'])
                ->name('indicator-templates.destroy');
        });

        Route::resource('accreditation-batches', StudyProgramAccreditationBatchController::class);
        Route::get(
            'accreditation-batches/{accreditationBatch}/enroll',
            [StudyProgramAccreditationBatchController::class, 'showEnroll']
        )->name('accreditation-batches.enroll.show');
        Route::post(
            'accreditation-batches/{accreditationBatch}/enroll',
            [StudyProgramAccreditationBatchController::class, 'enroll']
        )->name('accreditation-batches.enroll');

        Route::resource('accreditations', StudyProgramAccreditationController::class);
        Route::get('accreditations/{accreditation}/preview', [StudyProgramAccreditationController::class, 'preview'])
            ->name('accreditations.preview');
        Route::patch('accreditations/{accreditation}/complete', [StudyProgramAccreditationController::class, 'toggleComplete'])
            ->name('accreditations.complete');

        Route::prefix('accreditations/{accreditation}')->name('accreditations.')->group(function () {
            Route::get('indicators', [StudyProgramAccreditationIndicatorController::class, 'index'])
                ->name('indicators.index');
            Route::post('indicators/import', [StudyProgramAccreditationIndicatorController::class, 'import'])
                ->name('indicators.import');
            Route::get('indicators/create', [StudyProgramAccreditationIndicatorController::class, 'create'])
                ->name('indicators.create');
            Route::post('indicators', [StudyProgramAccreditationIndicatorController::class, 'store'])
                ->name('indicators.store');
            Route::delete('indicators/{indicator}', [StudyProgramAccreditationIndicatorController::class, 'destroy'])
                ->name('indicators.destroy');

            Route::get('input', [StudyProgramAccreditationInputController::class, 'index'])
                ->name('input.index');
            Route::get('input/{indicator}', [StudyProgramAccreditationInputController::class, 'edit'])
                ->name('input.edit');
            Route::put('input/{indicator}', [StudyProgramAccreditationInputController::class, 'update'])
                ->name('input.update');
        });
    });
});

// ============================================================
// Dashboard Operator (unchanged)
// ============================================================
Route::prefix('dashboard-operator')->name('dashboard-operator.')->middleware(['role:operator'])->group(function () {
    Route::get('/', [DashboardOperatorController::class, 'index'])->name('index');

    Route::resource('accreditations', OperatorAccreditationController::class);
    Route::get('accreditations/{accreditation}/preview', [OperatorAccreditationController::class, 'preview'])
        ->name('accreditations.preview');

    Route::get('accreditations/{accreditation}/indicators', [OperatorAccreditationIndicatorController::class, 'index'])
        ->name('accreditations.indicators.index');
    Route::post('accreditations/{accreditation}/indicators/import', [OperatorAccreditationIndicatorController::class, 'import'])
        ->name('accreditations.indicators.import');
    Route::get('accreditations/{accreditation}/indicators/create', [OperatorAccreditationIndicatorController::class, 'create'])
        ->name('accreditations.indicators.create');
    Route::post('accreditations/{accreditation}/indicators', [OperatorAccreditationIndicatorController::class, 'store'])
        ->name('accreditations.indicators.store');
    Route::delete('accreditations/{accreditation}/indicators/{indicator}', [OperatorAccreditationIndicatorController::class, 'destroy'])
        ->name('accreditations.indicators.destroy');

    Route::get('accreditations/{accreditation}/input', [OperatorAccreditationInputController::class, 'index'])
        ->name('accreditations.input.index');
    Route::get('accreditations/{accreditation}/input/{indicator}', [OperatorAccreditationInputController::class, 'edit'])
        ->name('accreditations.input.edit');
    Route::put('accreditations/{accreditation}/input/{indicator}', [OperatorAccreditationInputController::class, 'update'])
        ->name('accreditations.input.update');
});


require __DIR__ . '/auth.php';
