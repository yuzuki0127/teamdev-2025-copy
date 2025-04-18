<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
  return redirect()->route('login');
});

// アンケート回答
Route::get('survey/purpose', function () {return view('survey.purpose');});
Route::get('survey/{surveyId}/{employeeId}', [SurveyController::class, 'survey'])->name('survey');
Route::post('/survey/{surveyId}/{employeeId}', [SurveyController::class, 'storeAnswers'])->name('survey.answers.store');
Route::get('/survey/thanks', function () {return view('survey.thanks');})->name('survey.thanks');

// 会社登録・詳細
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/company-first-register', [DashboardController::class, 'companyFirstRegister'])
        ->name('company.first-register');
    Route::post('/dashboard/company-first-register', [DashboardController::class, 'storeCompany'])
        ->name('company.first-register.store');
});

Route::middleware(['auth', 'team'])->group(function () {
    // ユーザー設定機能
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ダッシュボード
    Route::get('/dashboard', [OverviewController::class, 'index'])->name('dashboard');

    // アンケート
    Route::get('survey/create', [SurveyController::class, 'create'])->name('survey.create');
    Route::post('/survey/create', [SurveyController::class, 'storeSurvey'])->name('survey.store');
    Route::post('survey/send', [SurveyController::class, 'sendSurveyEmail'])->name('survey.send');

  //可視化
    Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis.index');
    Route::get('/analysis/detail', [AnalysisController::class, 'detail'])->name('analysis.detail');
    Route::get('/analysis/past-plan', [AnalysisController::class, 'past'])->name('planning.past');
    Route::post('/planning/select', [AnalysisController::class, 'select'])->name('planning.select');

    //会社登録・詳細
    Route::get('/dashboard/company-register', function () {
        return view('dashboard.company-register');
    })->middleware(['auth'])->name('company.register');
    Route::get('/dashboard/company-detail', [DashboardController::class, 'detail'])
        ->name('dashboard.company-detail');
    // 編集画面表示
    Route::get('/dashboard/company-edit', [DashboardController::class, 'edit'])
        ->name('dashboard.company-edit');
    // 更新処理（PUT/PATCHメソッドを利用）
    Route::put('/dashboard/company', [DashboardController::class, 'update'])
        ->name('dashboard.company-update');

    // 実行機能
    Route::get('/action/create', [ActionController::class, 'showAdopting'])->name('action.create');
    Route::post('/action/create', [ActionController::class, 'storeAdopting'])->name('action.store');
    Route::get('/action/tasks', [ActionController::class, 'tasks'])->name('action.tasks');
    Route::post('/action/period/toggle', [ActionController::class, 'togglePeriod'])->name('action.period.toggle');
    Route::post('/action/{action}/stop', [ActionController::class, 'stopAction'])->name('action.stop');
    Route::post('/action/{action}/complete', [ActionController::class, 'completeAction'])->name('action.complete');
});

//ポートフォリオ
Route::get('/portfolio', function () {return view('portfolio');})->name('portfolio');

Route::post('/logout', function () {
    Auth::logout(); // ログアウト処理
    Session::flush(); // セッション全削除
    return redirect('/'); // トップページなどにリダイレクト
})->name('logout');

require __DIR__.'/auth.php';
