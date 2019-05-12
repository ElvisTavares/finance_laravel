<?php

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
    return view('/home');
});

Route::get('privacy', 'PrivacyController@index');
Route::get('terms', 'PrivacyController@terms');

Auth::routes();
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('accounts', 'AccountController');
Route::get('account/{account}/confirm', 'AccountController@confirm')->name('accounts.confirm');
Route::get('account/{account}/invoices', 'InvoiceController@index')->name('accounts.invoices');
Route::post('account/{account}/invoices', 'InvoiceController@store')->name('invoices.store');
Route::get('account/{account}/invoices/create', 'InvoiceController@create')->name('invoices.create');
Route::get('account/{account}/invoice/{invoice}/edit', 'InvoiceController@edit')->name('invoices.edit');
Route::get('account/{account}/invoice/{invoice}/confirm', 'InvoiceController@confirm')->name('invoices.confirm');
Route::get('account/{account}/invoice/{invoice}/transactions', 'TransactionController@index')->name('invoices.transactions');
Route::get('account/{account}/invoice/{invoice}/transaction/create', 'TransactionController@create')->name('invoices.transactions.create');

Route::post('account/{account}/upload/ofx', 'UploadController@ofx')->name('accounts.import.ofx');
Route::post('account/{account}/upload/csv', 'UploadController@csv')->name('accounts.import.csv');

Route::put('account/{account}/invoice/{invoice}', 'InvoiceController@update')->name('invoices.update');
Route::delete('account/{account}/invoice/{invoice}', 'InvoiceController@destroy')->name('invoices.destroy');
Route::post('account/{account}/transactions/category/', 'TransactionController@category')->name('accounts.transactions.categories');
Route::post('account/{account}/invoice/{invoice}/transactions/category/', 'TransactionController@category')->name('invoices.transactions.categories');
Route::post('account/{account}/invoice/{invoice}/upload/ofx', 'UploadController@ofx');
Route::post('account/{account}/invoice/{invoice}/upload/csv', 'UploadController@csv');


Route::get('account/{account}/transaction/{transaction}/repeat', function(Request $request, $accountId, $transactionId){
    return view('transactions.repeat', [
        'account' => Auth::user()->accounts()->findOrFail($accountId),
        'transaction' => Auth::user()->transactions($accountId)->findOrFail($transactionId)
    ]);
});

Route::get('account/{account}/transactions', 'TransactionController@index')->name('accounts.transactions');
Route::get('account/{account}/transaction/create', 'TransactionController@create')->name('accounts.transactions.create');
Route::post('account/{account}/transaction', 'TransactionController@store')->name('transactions.store');
Route::get('account/{account}/transaction/{transaction}/edit', 'TransactionController@edit')->name('transactions.edit');
Route::get('account/{account}/transaction/{transaction}/confirm', 'TransactionController@confirm')->name('transactions.confirm');
Route::put('account/{account}/transaction/{transaction}', 'TransactionController@update')->name('transactions.update');
Route::delete('account/{account}/transaction/{transaction}', 'TransactionController@destroy')->name('transactions.destroy');
Route::post('account/{account}/transaction/{transaction}/repeat', 'TransactionController@repeat')->name('transactions.repeat');

Route::get('users/{id}/confirm', 'UsersManagementController@confirm');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('users', 'UsersManagementController', [
    'names' => [
        'index'   => 'users',
        'destroy' => 'user.destroy',
    ],
]);

Route::middleware(['web', 'auth'])->group(function () {
    Route::post('search-users', 'UsersManagementController@search')->name('search-users');
});
