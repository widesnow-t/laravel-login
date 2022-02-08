<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BlogController;
/*use Illuminate\Support\Facades\Auth;*/

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

Route::middleware(['web'])->group(function () {
    // ログインフォーム表示
    Route::get('/', [AuthController::class, 'showLogin'])->name('login.show');
    // ログイン処理
    Route::post('login', [AuthController::class, 'login'])->name('login');
});
Route::middleware(['api'])->group(function () {
    // ホーム画面
    Route::get('home', function () {
        return view('home');
    })->name('home');
    // ログアウト
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');


// ブログ一覧画面を表示
Route::get('list', [BlogController::class,'list'])->name('blogs');

// ブログ登録画面を表示
Route::get('/blog/create', [BlogController::class,'showCreate'])->name('create');
// ブログ登録
Route::post('/blog/store', [BlogController::class, 'exeStore'])->name('store');

// ブログ詳細画面を表示
Route::get('/blog/{id}', [BlogController::class, 'showDetail'])->name('show');

// ブログ編集画面を表示
Route::get('/blog/edit/{id}', [BlogController::class, 'showEdit'])->name('edit');
Route::post('/blog/update', [BlogController::class, 'exeUpdate'])->name('update');
// ブログ削除
Route::post('/blog/delete/{id}', [BlogController::class, 'exeDelete'])->name('delete');
});