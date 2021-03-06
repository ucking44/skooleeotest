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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes(['register' => false]);

Route::group([
    'as' => 'admin.',
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['auth', 'admin']
],
function() {
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('company', 'CompanyController'); //->name('company.view');
    Route::resource('employee', 'EmployeeController');
    // Route::post('company-master', 'CompanyMasterController@store')->name('company.store');
    // Route::put('company-master/update', 'CompanyMasterController@update')->name('company.update');
});
