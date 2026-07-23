<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\EventController;
// use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TenantRegistrationController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\StaffController;

// use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $today = now()->toDateString();

    $events = \App\Models\Event::with('ticket_categories')
        ->where('status', 'active')
        ->whereDate('date', '>=', $today)
        ->latest()
        ->limit(6)
        ->get();

    // Get trending events (sorted by search_count DESC, then view_count DESC)
    $trending = \App\Models\Event::with('ticket_categories')
        ->where('status', 'active')
        ->whereDate('date', '>=', $today)
        ->orderByDesc('search_count')
        ->orderByDesc('view_count')
        ->limit(3)
        ->get();

    return view('home', compact('events', 'trending'));
})->name('home');


Route::view('/terms', 'terms')->name('terms');

Route::get('/become-creator', [VendorController::class, 'landingPage'])->name('vendor.landing');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/events/{id}',[EventController::class, 'show'])->name('event.detail');

/*
|--------------------------------------------------------------------------
| 2. AUTH ROUTES (Login & Register)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/register-vendor', [AuthController::class, 'showRegisterVendor'])->name('register.vendor.show');
    Route::post('/register-vendor', [AuthController::class, 'registerVendor'])->name('register.vendor');

    // Tenant Registration
    Route::get('/register-tenant', [TenantRegistrationController::class, 'showForm'])->name('register.tenant.show');
    Route::post('/register-tenant', [TenantRegistrationController::class, 'register'])->name('register.tenant.post');

    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');
    Route::get('/forgot-password/otp', [ForgotPasswordController::class, 'showOtpForm'])->name('password.otp');
    Route::post('/forgot-password/otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify');
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| 3. USER ROUTES (Profile & Booking/Transaction)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    
    // Redirect otomatis setelah login berdasarkan role
    Route::get('/home', function () {
        if (auth()->user()->role === 'vendor') return redirect()->route('vendor.dashboard');
        if (auth()->user()->role === 'admin') return redirect()->route('admin.dashboard');
        return redirect()->route('home');
    });

    // Profiling
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('my-tickets');
    Route::get('/my-messages', [ProfileController::class, 'myMessages'])->name('my-messages');
    Route::get('/my-messages/json', [ProfileController::class, 'myMessagesJson'])->name('my-messages.json');
    Route::get('/show-ticket/{id}', [TicketController::class, 'showTicket'])->name('ticket.show');
    Route::get('/download-ticket/{id}', [TicketController::class, 'downloadTicket'])->name('ticket.download');
    Route::get('/qr-code/{bookingCode}', [TicketController::class, 'generateQrCode'])->name('ticket.qrcode');
    Route::get('/order-history', [ProfileController::class, 'orderHistory'])->name('order-history');

    // BOOKING ROUTES: Bisa diakses oleh User Biasa (Pembeli)
    // Gunakan rute ini di tombol "Beli" pada event-detail
    Route::get('/booking/create/{event_id}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/store/{event_id}', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/payment/{booking_id}', [BookingController::class, 'payment'])->name('booking.payment');
    Route::get('/booking/success/{booking_id}', [BookingController::class, 'success'])->name('booking.success');
    Route::post('/booking/expire/{booking_id}', [BookingController::class, 'expire'])->name('booking.expire');

    // Vendor Re-registration (untuk yang ditolak)
    Route::get('/register-vendor/reapply', [AuthController::class, 'showReapplyVendor'])->name('register.vendor.reapply');
    Route::post('/register-vendor/reapply', [AuthController::class, 'reapplyVendor'])->name('register.vendor.reapply.post');
    
    // Tenant Registration to Event
    Route::get('/event/{event}/join', [TenantRegistrationController::class, 'joinEventForm'])->name('tenant.event.join');
    Route::get('/event/{event}/join/form', [TenantRegistrationController::class, 'joinEventMultiStepForm'])->name('tenant.event.join.step-form');
    Route::post('/event/{event}/join', [TenantRegistrationController::class, 'joinEventStore'])->name('tenant.event.join.store');

    // Tenant Booth Status & Payment
    Route::get('/booths', [App\Http\Controllers\TenantBoothController::class, 'index'])->name('tenant.booths.index');
    Route::post('/booths/{eventTenant}/pay', [App\Http\Controllers\TenantBoothController::class, 'pay'])->name('tenant.booths.pay');
    Route::post('/booths/{eventTenant}/refund', [App\Http\Controllers\TenantBoothController::class, 'requestRefund'])->name('tenant.booths.refund');
});

// Midtrans Callback (Public)
Route::post('/midtrans/callback', [BookingController::class, 'callback'])->name('midtrans.callback');

/*
|--------------------------------------------------------------------------
| 4. VENDOR ROUTES (Management Only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    
    Route::get('/home', [VendorController::class, 'homeVendor'])->name('home');
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');

    // Event Management
    Route::get('/tenants', [VendorController::class, 'tenants'])->name('tenants.index');
    Route::patch('/tenants/{eventTenant}/verify', [VendorController::class, 'verifyTenant'])->name('tenants.verify');
    Route::patch('/tenants/{eventTenant}/refund', [VendorController::class, 'refundTenant'])->name('tenants.refund');

    Route::get('/events', [VendorController::class, 'indexEvent'])->name('events.index');
    Route::get('/events/create', [VendorController::class, 'createEvent'])->name('events.create');
    Route::post('/events', [VendorController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{event}/edit', [VendorController::class, 'editEvent'])->name('events.edit');
    Route::put('/events/{event}', [VendorController::class, 'updateEvent'])->name('events.update');
    Route::patch('/events/{event}/toggle-status', [VendorController::class, 'toggleEventStatus'])->name('events.toggle-status');
    Route::delete('/events/{event}', [VendorController::class, 'destroyEvent'])->name('events.destroy');

    // Resources
    Route::get('/merchandise-collection', [VendorController::class, 'merchandiseCollection'])->name('merchandise.collection');
    Route::resource('merchandises', MerchandiseController::class);
    Route::resource('ticket_categories', TicketCategoryController::class);

    // Bookings & Reports
    Route::get('/bookings', [VendorController::class, 'indexBooking'])->name('bookings.index');
    Route::get('/bookings/export', [VendorController::class, 'exportBookings'])->name('bookings.export');
    Route::get('/laporan', [VendorController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/export', [VendorController::class, 'exportLaporan'])->name('laporan.export');
    Route::get('/laporan/{id}', [VendorController::class, 'laporanDetail'])->name('laporan.detail');
    Route::get('/laporan/{id}/export', [VendorController::class, 'exportLaporanDetail'])->name('laporan.detail.export');
    Route::get('/pengguna-tiket', [VendorController::class, 'penggunaTiket'])->name('pengguna-tiket');
    Route::get('/pengguna-tiket/{id}', [VendorController::class, 'penggunaTiketDetail'])->name('pengguna-tiket.detail');
    
    // Account & Settings
    Route::get('/informasi-dasar', [VendorController::class, 'informasiDasar'])->name('informasi-dasar');
    Route::get('/informasi-legal', [VendorController::class, 'informasiLegal'])->name('informasi-legal');
    Route::get('/rekening', [VendorController::class, 'rekening'])->name('rekening');
    Route::post('/rekening/withdraw', [VendorController::class, 'storeWithdrawal'])->name('rekening.withdraw');
    Route::get('/rekening/export-event/{id}', [VendorController::class, 'exportRekeningEvent'])
    ->name('rekening.exportEvent');

    // Scanner
    Route::get('/scanner', [TicketController::class, 'scanner'])->name('scanner');
    Route::post('/scanner/scan', [TicketController::class, 'scan'])->name('scan');

    // manajement staff (hanya vendor asli dan bukan vendor staff)
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');

});

// Route scanner ( hanya boleh diakses vendor dan staff vendor_staff)
Route::prefix('vendor/staff')->middleware(['auth', 'vendor_staff_access'])->name('vendor.staff.')->group(function () {
    Route::get('/scanner', [StaffController::class, 'scanner'])->name('scanner');
    Route::post('/scanner/scan', [StaffController::class, 'scan'])->name('scanner.scan');
    Route::get('/scanner/history', [StaffController::class, 'scanHistory'])->name('scanner.history');
});

/*
|--------------------------------------------------------------------------
| 5. ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', AdminUsersController::class);
    Route::patch('/users/{user}/toggle-status', [AdminUsersController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::patch('/users/{user}/verify', [AdminUsersController::class, 'verify'])->name('users.verify');
 
    // Vendor Verification (halaman terpisah)
    Route::get('/vendor-verification', [AdminUsersController::class, 'vendorVerification'])->name('vendor.verification');
    Route::get('/tenant-verification', [AdminUsersController::class, 'tenantVerification'])->name('tenant.verification');
 
    Route::get('/sales', [SalesController::class, 'index'])->name('sales');
    Route::get('/tickets', [SalesController::class, 'tickets'])->name('tickets.index');
    Route::get('/income', [SalesController::class, 'income'])->name('income');
    Route::patch('/withdrawal/{id}/approve', [SalesController::class, 'approveWithdrawal'])->name('withdrawal.approve');
    Route::patch('/withdrawal/{id}/reject', [SalesController::class, 'rejectWithdrawal'])->name('withdrawal.reject');
 
    // Finance Routes
    Route::get('/finance/service-fee', [App\Http\Controllers\Admin\FinanceController::class, 'serviceFeeAccount'])->name('finance.service-fee');
    Route::get('/finance/tenant-service-fee', function () {
        return redirect()->route('admin.finance.service-fee', ['tab' => 'tenant']);
    })->name('finance.tenant-service-fee');
    Route::get('/finance/tax', [App\Http\Controllers\Admin\FinanceController::class, 'taxAccount'])->name('finance.tax');
    Route::post('/finance/tax/{eventId}/remit-event', [App\Http\Controllers\Admin\FinanceController::class, 'remitTaxByEvent'])->name('finance.tax.remit-event');
    // routes/web.php
    Route::get('finance/tax/{eventId}/receipt', [FinanceController::class, 'getTaxReceipt']);
    
    // Refund Tenant - Admin
    Route::get('/refund-tenant', [App\Http\Controllers\Admin\AdminRefundController::class, 'index'])->name('refund.index');
    Route::post('/refund-tenant/{eventTenant}/process', [App\Http\Controllers\Admin\AdminRefundController::class, 'process'])->name('refund.process');
    // Contact Messages
    Route::get('/contact', [AdminContactController::class, 'index'])->name('contact.index');
    Route::get('/contact/{id}', [AdminContactController::class, 'show'])->name('contact.show');
    Route::put('/contact/{id}', [AdminContactController::class, 'update'])->name('contact.update');
    Route::delete('/contact/{id}', [AdminContactController::class, 'destroy'])->name('contact.destroy');
    Route::get('/contact/{id}/download-photo', [AdminContactController::class, 'downloadPhoto'])->name('contact.download');
    Route::get('/contact/export/csv', [AdminContactController::class, 'export'])->name('contact.export');
 
    // Reports 
    Route::get('/reports/users', [ReportController::class, 'userReportForm'])->name('reports.user.form');
    Route::post('/reports/users/print', [ReportController::class, 'printUserReport'])->name('reports.user.print');
    // Repots vendor
    Route::get('/reports/sales', [ReportController::class, 'salesReportForm'])->name('reports.sales.form');
    Route::post('/reports/sales/print', [ReportController::class, 'printSalesReport'])->name('reports.sales.print');
    // Reports Tenant
    Route::get('/reports/tenant/form', [ReportController::class, 'tenantReportForm'])->name('reports.tenant.form');
    Route::post('/reports/tenant/print', [ReportController::class, 'printTenantReport'])->name('reports.tenant.print');
    // Reports to Owner
    Route::get('/reports/owner', [ReportController::class, 'ownerReportForm'])->name('reports.owner.form');
    Route::post('/reports/owner/send', [ReportController::class, 'sendOwnerReport'])->name('reports.owner.send');
    
});

// ==================== OWNER ====================
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard',[OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports', [OwnerController::class, 'reports'])->name('reports');
    Route::get('/reports/{report}', [OwnerController::class, 'showReport'])->name('reports.show');
    Route::get('/reports/{report}/download', [OwnerController::class, 'downloadReport'])->name('reports.download');
});

//API Google
Route::get('/auth/redirect', [AuthController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'callback'])->name('auth.callback');