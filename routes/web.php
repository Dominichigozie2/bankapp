<?php

use App\Http\Controllers\TransferController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminTransferController;
use App\Http\Controllers\Admin\AdminUserCodeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminSettingController;
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
use App\Http\Controllers\AdminDepositCodeController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\BankStatementController;
use App\Http\Controllers\Admin\CryptoTypeController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\ActivityController;

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



// Admin routes (protect with admin middleware / guard)






Route::middleware(['auth'])->group(function () {
    // ==========================
    // 🔹 ADMIN ROUTES
    // ==========================
    Route::prefix('admin')->group(function () {
        // Transfer settings (no extra subfolder)
        Route::get('transfer_settings', [AdminSettingsController::class, 'edit'])->name('admin.transfer.edit');
        Route::post('transfer_settings', [AdminSettingsController::class, 'update'])->name('admin.transfer_settings.update');

        Route::get('/transfer_settings', [AdminSettingsController::class, 'edit'])->name('admin.transfer_settings.edit');

        Route::put('/transfer_settings/update', [AdminSettingsController::class, 'update'])->name('admin.transfer_settings.update');




        Route::get('/codes', [AdminUserCodeController::class, 'index'])->name('admin.codes.index');
        Route::get('/codes/{id}/data', [AdminUserCodeController::class, 'getUserCodes']);
        Route::post('/codes/{id}/update', [AdminUserCodeController::class, 'update']);






        Route::post('/send-email', [AdminDashboardController::class, 'sendEmail'])->name('admin.sendEmail');

        Route::get('/credit-debit', [AdminDashboardController::class, 'creditDebitForm'])->name('admin.creditdebit');
        Route::post('/credit-debit', [AdminDashboardController::class, 'creditDebitProcess'])->name('admin.creditdebit.process');

        Route::get('/credit-debit', [AdminDashboardController::class, 'creditDebitForm'])->name('admin.creditdebit');
    });
    // ==========================
    // 🔹 USER ROUTES
    // ==========================
    Route::prefix('account')->group(function () {
        Route::get('transfer', [TransferController::class, 'index'])->name('user.transfer');
        Route::post('transfer/local', [TransferController::class, 'storeLocal'])->name('user.transfer.local');
        Route::post('transfer/international', [TransferController::class, 'storeInternational'])->name('user.transfer.international');
        Route::post('transfer/self', [TransferController::class, 'selfTransfer'])->name('user.transfer.self');
        Route::post('verify-codes', [TransferController::class, 'verifyCodes'])->name('user.verify.codes');
        Route::get('transfers', [TransferController::class, 'history'])->name('user.transfers.history');

        Route::post('/transfer/email-preview', [TransferController::class, 'emailPreview'])
    ->name('user.transfer.emailPreview');


        Route::get('transfer/{id}/invoice', [TransferController::class, 'invoice'])->name('user.transfer.invoice');

        Route::post('/account/verify-single-code', [TransferController::class, 'verifySingleCode'])
            ->name('user.verify.singlecode');

        Route::post('/deposit/email-preview', [DepositController::class, 'emailPreview'])->name('user.deposit.emailPreview');
    });




    Route::get('admin/transfers', [AdminTransferController::class, 'index'])->name('admin.transfer.index');

    Route::post('admin/transfers/{id}/approve', [AdminTransferController::class, 'approve'])->name('admin.transfer.approve');

    Route::post('admin/transfers/{id}/reject', [AdminTransferController::class, 'reject'])->name('admin.transfer.reject');



    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');



    Route::get('/account/dashboard', [DashboardController::class, 'dashboard'])->name('account.user.index');



    Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');


    Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user.index');

    Route::post('/admin/user', [UserController::class, 'store'])->name('admin.user.store');

    Route::get('/admin/user/view/{id}', [UserController::class, 'show'])->name('admin.user.show');

    Route::post('/admin/user/verify/{id}', [UserController::class, 'verify'])->name('admin.user.verify');

    Route::delete('/admin/user/delete/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');

    Route::post('admin/user/ban/{id}', [UserController::class, 'toggleBan'])->name('admin.user.ban');


    // Show deposit page
    Route::get('/account/deposit', [DepositController::class, 'create'])->name('user.deposit.create');

    Route::post('account/deposit/verify-multiple-codes', [DepositController::class, 'verifyMultipleCodes'])->name('user.deposit.verifyMultipleCodes');

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

    Route::get('/admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile');

    // Update user profile
    Route::post('/admin/profile/update', [AdminProfileController::class, 'updateProfile'])->name('admin.profile.update');

    // Update password
    Route::post('/admin/password/update', [AdminProfileController::class, 'updatePassword'])->name('admin.password.update');

    // Update password
    Route::post('/user/password/update', [UserProfileController::class, 'updatePassword'])->name('user.password.update');

    // Update passcode
    Route::post('/user/passcode/update', [UserProfileController::class, 'updatePasscode'])->name('user.passcode.update');


    // USER
    Route::get('/account/cards', [CardController::class, 'showUserCardPage'])->name('user.cards');
    Route::post('/user/request-card', [CardController::class, 'requestCard'])->name('user.cards.request');

    Route::post('/account/cards/deactivate', [CardController::class, 'deactivate'])->name('user.cards.deactivate');


    // ADMIN
    Route::get('/admin/cards', [AdminCardController::class, 'index'])->name('admin.cards');
    Route::post('/admin/cards/approve/{id}', [AdminCardController::class, 'approve'])->name('admin.cards.approve');
    Route::post('/admin/cards/hold/{id}', [AdminCardController::class, 'hold'])->name('admin.cards.hold');
    Route::post('/admin/cards/reject/{id}', [AdminCardController::class, 'reject'])->name('admin.cards.reject');



    Route::get('admin/crypto_types', [CryptoTypeController::class, 'index'])->name('admin.crypto_types');
    Route::post('admin/crypto_types', [CryptoTypeController::class, 'store'])->name('admin.crypto_types.store');
    Route::get('admin/crypto_types/{cryptoType}', [CryptoTypeController::class, 'show'])->name('admin.crypto_types.show');
    Route::put('admin/crypto_types/{cryptoType}', [CryptoTypeController::class, 'update'])->name('admin.crypto_types.update');
    Route::delete('admin/crypto_types/{cryptoType}', [CryptoTypeController::class, 'destroy'])->name('admin.crypto_types.destroy');



    Route::get('/account/loans', [LoanController::class, 'index'])->name('user.loan');

    Route::post('/user/loans/request', [LoanController::class, 'requestLoan'])->name('user.loan.request');

    Route::post('/account/loan/repay/{id}', [LoanController::class, 'repayLoan'])->name('user.loan.repay');

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

    Route::get('/account/demo', function () {
        return view('account.user.demo');
    })->name('user.demo');




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


    Route::get('/account/deposit', [DepositController::class, 'create'])->name('user.deposit.create');
    Route::post('/account/deposit', [DepositController::class, 'store'])->name('user.deposit.store');
    Route::post('/account/deposit/store', [DepositController::class, 'store'])
        ->name('user.deposit.store');
    Route::post('/deposit/verify-single-code', [DepositController::class, 'verifySingleCode'])
        ->name('user.deposit.verifySingleCode');


    // code checks
    Route::get('/account/deposit/code-required', [DepositController::class, 'codeRequired'])->name('user.deposit.codeRequired');
    Route::post('/account/deposit/verify-code', [DepositController::class, 'verifyCode'])->name('user.deposit.verifyCode');

    // Admin deposit code management
    Route::get('/admin/depositcodes', [AdminDepositCodeController::class, 'index'])->name('admin.depositcodes');
    Route::post('/admin/depositcodes/generate', [AdminDepositCodeController::class, 'generate'])->name('admin.depositcodes.generate');
    Route::post('/admin/depositcodes/revoke/{id}', [AdminDepositCodeController::class, 'revoke'])->name('admin.depositcodes.revoke');


    Route::get('/admin/settings', [AdminSettingController::class, 'index'])
        ->name('admin.settings.index');

    Route::post('/admin/settings/deposit-code-toggle', [AdminSettingController::class, 'depositCodeToggle'])
        ->name('admin.settings.depositCodeToggle');



    Route::get('admin/accounttypes', [AccountTypeController::class, 'index'])->name('admin.accounttypes.index');
    Route::post('admin/accounttypes/store', [AccountTypeController::class, 'store'])->name('admin.accounttypes.store');
    Route::delete('admin/accounttypes/delete/{id}', [AccountTypeController::class, 'delete'])->name('admin.accounttypes.delete');


    // Display the bank statement with filters and pagination
    Route::get('/account/bank-statement', [BankStatementController::class, 'index'])
        ->name('user.bank_statement');

    Route::get('/account/bank-statement/download', [BankStatementController::class, 'download'])->name('account.bank.statement.download');

    Route::get('/account/activities', [ActivityController::class, 'index'])->name('user.activities');
});
