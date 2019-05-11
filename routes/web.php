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
Route::get('/transactions', 'TransactionController@index');
Route::get('/transactions/charts', 'TransactionController@charts');
Route::get('/transactions/create', 'TransactionController@create');
Route::put('/transactions/addCategories', 'TransactionController@addCategories');
Route::get('account/{account}/confirm', 'AccountController@confirm')->name('accounts.confirm');
Route::get('account/{account}/invoices', 'InvoiceController@index')->name('accounts.invoices');
Route::get('account/{account}/transactions', 'TransactionController@index')->name('accounts.transactions');

Route::get('account/{account}/transaction/create', 'TransactionController@create');
Route::post('account/{account}/transaction', 'TransactionController@store');
Route::post('account/{account}/upload/ofx', 'UploadController@ofx')->name('accounts.import.ofx');
Route::post('account/{account}/upload/csv', 'UploadController@csv')->name('accounts.import.csv');

Route::get('account/{account}/invoice/create', 'InvoiceController@create');
Route::post('account/{account}/invoice', 'InvoiceController@store');
Route::put('account/{account}/transactions/addCategories', 'TransactionController@addCategories');
Route::get('account/{account}/transaction/{transaction}/edit', 'TransactionController@edit');
Route::get('account/{account}/transaction/{transaction}/confirm', 'TransactionController@confirm');
Route::put('account/{account}/transaction/{transaction}', 'TransactionController@update');
Route::delete('account/{account}/transaction/{transaction}', 'TransactionController@destroy');
Route::get('account/{account}/transaction/{transaction}/repeat', 'TransactionController@repeat');
Route::post('account/{account}/transaction/{transaction}/confirmRepeat', 'TransactionController@confirmRepeat');
Route::get('account/{account}/invoice/{invoice}/edit', 'InvoiceController@edit');
Route::get('account/{account}/invoice/{invoice}/confirm', 'InvoiceController@confirm');
Route::put('account/{account}/invoice/{invoice}', 'InvoiceController@update');
Route::delete('account/{account}/invoice/{invoice}', 'InvoiceController@destroy');
Route::post('account/{account}/invoice/{invoice}/upload/ofx', 'UploadController@ofx');
Route::post('account/{account}/invoice/{invoice}/upload/csv', 'UploadController@csv');

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
