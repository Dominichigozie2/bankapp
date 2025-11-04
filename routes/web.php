<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDepositController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\AdminCardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\AdminLoanController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminTicketController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::post('/register', [RegisterController::class, 'store'])->name('register.post');


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::post('/user/save-passcode', [LoginController::class, 'savePasscode'])->name('user.save.passcode');

Route::post('/user/verify-passcode', [LoginController::class, 'verifyPasscode'])->name('user.verify.passcode');

Route::post('/admin/toggle-passcode/{user}', [AdminController::class, 'togglePasscode']);


Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.post');



Route::middleware(['auth'])->group(function () {


    Route::get('/account/dashboard', [DashboardController::class, 'dashboard'])->name('account.user.index');

    Route::get('/admin/dashboard', function () {
        return view('account.admin.index');
    })->name('admin.dashboard');

    Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');


    Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user.index');

    Route::post('/admin/user', [UserController::class, 'store'])->name('admin.user.store');

    Route::get('/admin/user/view/{id}', [UserController::class, 'show'])->name('admin.user.show');

    Route::post('/admin/user/verify/{id}', [UserController::class, 'verify'])->name('admin.user.verify');

    Route::delete('/admin/user/delete/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');

    // Show deposit page
    Route::get('/account/deposit', [DepositController::class, 'create'])->name('user.deposit.create');

    // Admin KYC Management
    Route::get('/admin/kyc', [AdminController::class, 'kycIndex'])->name('admin.kyc.index');

    Route::post('/admin/kyc/approve/{id}', [AdminController::class, 'approveKYC'])->name('admin.kyc.approve');

    Route::post('/admin/kyc/reject/{id}', [AdminController::class, 'rejectKYC'])->name('admin.kyc.reject');


    // Handle deposit submission
    Route::post('/account/deposit', [DepositController::class, 'store'])->name('user.deposit.store');

    Route::get('admin/deposit', [AdminDepositController::class, 'index'])->name('admin.deposit');

    Route::post('/admin/deposit/approve/{id}', [AdminDepositController::class, 'approve'])->name('admin.deposit.approve');

    Route::post('admin/deposit/reject/{id}', [AdminDepositController::class, 'reject'])->name('admin.deposit.reject');

    // Profile page view
    Route::get('/account/profile', [UserProfileController::class, 'index'])->name('account.profile');

    // Update user profile
    Route::post('/user/profile/update', [UserProfileController::class, 'updateProfile'])->name('user.profile.update');

    // Update password
    Route::post('/user/password/update', [UserProfileController::class, 'updatePassword'])->name('user.password.update');

    // Update passcode
    Route::post('/user/passcode/update', [UserProfileController::class, 'updatePasscode'])->name('user.passcode.update');


    // USER
    Route::get('/account/cards', [CardController::class, 'showUserCardPage'])->name('user.cards');
    Route::post('/user/request-card', [CardController::class, 'requestCard'])->name('user.cards.request');

    // ADMIN
    Route::get('/admin/cards', [AdminCardController::class, 'index'])->name('admin.cards');
    Route::post('/admin/cards/approve/{id}', [AdminCardController::class, 'approve'])->name('admin.cards.approve');
    Route::post('/admin/cards/hold/{id}', [AdminCardController::class, 'hold'])->name('admin.cards.hold');
    Route::post('/admin/cards/reject/{id}', [AdminCardController::class, 'reject'])->name('admin.cards.reject');


    Route::get('/account/transfer', function () {
        return view('account.user.transfer');
    })->name('user.transfer');

    // Route::get('/account/loanhistory', function () {
    //     return view('account.user.loanhistory');
    // })->name('user.loanhistory');


    Route::get('/account/loans', [LoanController::class, 'index'])->name('user.loan');

    Route::post('/user/loans/request', [LoanController::class, 'requestLoan'])->name('user.loan.request');

    Route::post('/user/validate-passcode', [LoanController::class, 'validatePasscode'])->name('user.validate.passcode');



    // Show loan history
    Route::get('/account/loanhistory', [LoanController::class, 'history'])->name('user.loanhistory');

    Route::get('/admin/loans', [AdminLoanController::class, 'index'])->name('admin.loan');

    Route::post('/admin/loans/approve/{id}', [AdminLoanController::class, 'approve'])->name('admin.loan.approve');

    Route::post('/admin/loans/hold/{id}', [AdminLoanController::class, 'hold'])->name('admin.loan.hold');

    Route::post('/admin/loans/reject/{id}', [AdminLoanController::class, 'reject'])->name('admin.loan.reject');

    Route::post('/admin/loans/limit', [AdminLoanController::class, 'setLimit'])->name('admin.loan.limit');

    Route::get('/account/bankhistory', function () {
        return view('account.user.bankhistory');
    })->name('user.bankhistory');

    Route::get('/account/profile', function () {
        return view('account.user.profile');
    })->name('user.profile');

    Route::get('/account/kyc', function () {
        return view('account.user.kyc');
    })->name('user.kyc');

    Route::post('/user/kyc/submit', [UserProfileController::class, 'submitKYC'])
        ->name('user.kyc.submit');

    Route::get('/account/report', function () {
        return view('account.user.report');
    })->name('user.kyc');




    // User routes
    Route::get('/account/tickets', [TicketController::class, 'index'])->name('user.tickets.index'); // list user's tickets
    Route::get('/account/tickets/create', [TicketController::class, 'create'])->name('user.tickets.create');
    Route::post('/account/tickets', [TicketController::class, 'store'])->name('user.tickets.store');
    Route::get('/account/tickets/{ticket}', [TicketController::class, 'show'])->name('user.tickets.show');
    Route::post('/account/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('user.tickets.reply');
    Route::post('/account/tickets/{ticket}/close', [TicketController::class, 'close'])->name('user.tickets.close');

    Route::get('/account/tickethistory', [TicketController::class, 'history'])->name('user.tickethistory');

    Route::post('/account/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('user.tickets.reply');

    Route::get('/account/tickets/{id}/fetch', [TicketController::class, 'fetch']);

    // ADMIN SUPPORT TICKETS
    Route::get('/admin/tickets', [AdminTicketController::class, 'index'])->name('admin.tickets');
    Route::get('/admin/tickets/{id}/fetch', [AdminTicketController::class, 'fetch'])->name('admin.tickets.fetch');
    Route::post('/admin/tickets/{id}/reply', [AdminTicketController::class, 'reply'])->name('admin.tickets.reply');
    Route::post('/admin/tickets/{id}/close', [AdminTicketController::class, 'close'])->name('admin.tickets.close');
});
