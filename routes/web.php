<?php

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
//Auth::routes(['register' => false]);
Auth::routes();

Route::get('/', function () {
    return view('auth.login');
});


Route::group(['middleware' => ['auth']], function() {

    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');

});



Route::get('/home', 'HomeController@index')->name('home')->middleware('check_status');

Route::resource('invoices', 'InvoiceController');
Route::get('status_show/{id}', 'InvoiceController@status_show')->name('status_show');
Route::post('status_update/{id}', 'InvoiceController@status_update')->name('status_update');
Route::get('edit_invoice/{id}', 'InvoiceController@edit');
Route::get('/invoices_details/{id}', 'InvoicesDetailsController@invoices_details');
Route::get('invoices_paid', "InvoiceController@invoice_paid");
Route::get('invoices_unpaid', "InvoiceController@invoice_unpaid");
Route::get('invoices_partial', "InvoiceController@invoice_partial");

Route::get('invoices_archives', "InvoiceArchiveController@index");
Route::post('invoices_archives_delete', "InvoiceArchiveController@destroy")->name('invoices.archives.destroy');
Route::post('restore_invoice', "InvoiceArchiveController@restore_invoice")->name('restore.invoice');

Route::get('print_invoice/{id}', "InvoiceController@print_invoice")->name('print.invoice');
Route::get('export_invoices', 'InvoiceController@export');

Route::get('/section/{id}', 'InvoiceController@getProducts');  //get products of section by ajax

Route::get('/view_file/{invoice_number}/{file_name}', 'InvoicesDetailsController@open_file');
Route::get('/download/{invoice_number}/{file_name}', 'InvoicesDetailsController@download_file');
Route::post('delete_file', 'InvoicesDetailsController@delete_file')->name('delete_file');

Route::resource('invoice_attachments', 'InvoicesAttachmentsController');

Route::resource('sections', 'SectionController');

Route::resource('products', 'ProductController');

Route::get('invoices_report', 'InvoicesReportController@index');
Route::post('search_invoices', 'InvoicesReportController@search_invoices')->name('search_invoices');

Route::get('customer_report', 'CustomerReportController@index');
Route::post('search_invoices', 'CustomerReportController@search_invoices')->name('customer_search_invoices');

Route::get('markasread_all', 'NotificationsController@markAsReadAll')->name('mark_as_read_all');
Route::get('/invoice_details/{id}/{notify_id}', 'NotificationsController@invoices_details');

Route::get('/{page}', 'AdminController@index');


