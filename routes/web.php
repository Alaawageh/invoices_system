<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\CustomReportController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\InvoiceReportController;

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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/{page}', [AdminController::class,'index']);

Route::resource('/invoices', InvoicesController::class);
Route::resource('/sections', SectionController::class);
Route::resource('/products', ProductController::class);
Route::get('section/{id}',[InvoicesController::class,'getproducts']);
Route::get('invoicesDetails/{id}',[InvoiceDetailsController::class,'edit']);
Route::get('view_file/{invoice_number}/{file_name}',[InvoiceDetailsController::class,'openFile']);
Route::get('download_file/{invoice_number}/{file_name}',[InvoiceDetailsController::class,'get_file']);
Route::post('delete_file', [InvoiceDetailsController::class,'destroy'])->name('delete_file');
Route::resource('invoiceAttachment',InvoiceAttachmentController::class);
Route::get('edit_invoice/{id}',[InvoicesController::class,'edit']);
Route::get('status_show/{id}',[InvoicesController::class,'show'])->name('status_show');
Route::post('status_update/{id}',[InvoicesController::class,'status_update'])->name('status_update');
Route::get('invoice_paid',[InvoicesController::class,'invoice_paid'])->name('invoice_paid');
Route::get('invoice_unpaid',[InvoicesController::class,'invoice_unpaid'])->name('invoice_unpaid');
Route::get('invoice_part',[InvoicesController::class,'invoice_part'])->name('invoice_part');
Route::resource('invoice_archive',ArchiveController::class);
Route::get('print_invoice/{id}',[InvoicesController::class,'print_invoice'])->name('print_invoice');
Route::get('export_invoice', [InvoicesController::class, 'export'])->name('export_invoice');
Route::get('invoice_report',[InvoiceReportController::class,'index']);
Route::post('Search_report',[InvoiceReportController::class,'search']);
Route::get('custom_report',[CustomReportController::class,'index']);
Route::post('search_report',[CustomReportController::class,'search_report']);
Route::get('MarkAsRead',[InvoicesController::class,'MarkAsRead'])->name('MarkAsRead');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});
