<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Home\HomeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/signup', [AuthController::class, 'signupForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);

Route::get('/account-verify', [AuthController::class, 'accountVerifyForm'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect()->route('home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/reset-password', [AuthController::class, 'resetPassForm'])->name('resetPass');

Route::middleware(['auth'])->prefix('/admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/history-pay', [AdminController::class, 'historyPay'])->name('historyPay');
    Route::get('/wallet', [AdminController::class, 'wallet'])->name('wallet');
    Route::get('/recharge-pack', [AdminController::class, 'rechargePack'])->name('rechargePack');
    Route::get('/pay', [AdminController::class, 'payForm'])->name('pay');
    Route::post('/cancel-pay', [AdminController::class, 'cancelPay'])->name('cancelPay');

    Route::get('/create-transaction', [AdminController::class, 'createTransaction'])->name('createTransaction');
    Route::post('/send-transaction', [AdminController::class, 'sendTransaction'])->name('sendTransaction');

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/list-pay', [AdminController::class, 'listPay'])->name('listPay');
        Route::get('/ajax-get-pay', [AdminController::class, 'dataReponse'])->name('ajax-get-pay');
        Route::post('/confirmation', [AdminController::class, 'confirmationAdmin'])->name('confirmation');
    });

    Route::middleware(['auth', 'role:admin'])->prefix('/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'listUsers'])->name('listUsers');
        Route::get('/edit/{id}', [UserController::class, 'editUserForm'])->name('editUser');
        Route::post('/edit/{id}', [UserController::class, 'editUser']);
        Route::post('/change-status', [UserController::class, 'changeStatus'])->name('changeStatus');
    });
});
