<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('pages.home');
//})->name('home');

// Home
Route::get('/', 'App\Http\Controllers\Auth\LoginController@showSignInPage')->name('login');

// Sign In, Sign Up, Sign Out
Route::post('/doLogin', 'App\Http\Controllers\Auth\LoginController@doLogin');
Route::get('/signout', 'App\Http\Controllers\Auth\LogoutController@doLogout')->name('logout');

// Dashboard page
Route::group(['middleware' => ['webclientauth']], function () {
    Route::get('/dashboard', 'App\Http\Controllers\Dashboard\DashboardController@showPage')->name('dashboard');
    Route::post('/dashboard', 'App\Http\Controllers\Dashboard\DashboardController@showPage')->name('dashboard');
    Route::get('/inputcompany', 'App\Http\Controllers\Dashboard\DashboardController@showInputPage');
    Route::post('/inputcompany', 'App\Http\Controllers\Dashboard\DashboardController@showInputPage');
    Route::post('/submitcompany', 'App\Http\Controllers\Dashboard\DashboardController@submitCompany');
    // Route::prefix('admin')->group(function () {
    //     Route::get('/token', 'App\Http\Controllers\Admin\TokenController@showPage')->name('admintoken');
    //     Route::post('/token', 'App\Http\Controllers\Admin\TokenController@showPage')->name('admintoken');
    //     Route::post('/inputtoken', 'App\Http\Controllers\Admin\TokenController@inputToken');
    // });
});

// Route::middleware(['adminauth'])->prefix('admin')->group(function () {
//     Route::get('/token', 'App\Http\Controllers\Admin\TokenController@showPage')->name('admintoken');
//     Route::post('/token', 'App\Http\Controllers\Admin\TokenController@showPage')->name('admintoken');
// });

