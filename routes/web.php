<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// Public homepage
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Volt::route('dashboard', 'pages.dashboard')->name('pages.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Accounts Management
    |--------------------------------------------------------------------------
    */
    Volt::route('accounts', 'pages.accounts.index')->name('pages.accounts.index');
    Volt::route('accounts/add', 'pages.accounts.create')->name('pages.accounts.create');
    Volt::route('accounts/{account}', 'pages.accounts.show')->name('pages.accounts.show');
    Volt::route('accounts/{account}/edit', 'pages.accounts.edit')->name('pages.accounts.edit');

    /*
    |--------------------------------------------------------------------------
    | Transactions Management
    |--------------------------------------------------------------------------
    */
    Volt::route('transactions', 'pages.transactions.index')->name('pages.transactions.index');
    Volt::route('transactions/add', 'pages.transactions.create')->name('pages.transactions.create');
    Volt::route('transactions/{transaction}', 'pages.transactions.show')->name('pages.transactions.show');
    Volt::route('transactions/{transaction}/edit', 'pages.transactions.edit')->name('pages.transactions.edit');

    /*
    |--------------------------------------------------------------------------
    | Categories Management
    |--------------------------------------------------------------------------
    */
    Volt::route('categories', 'pages.categories.index')->name('pages.categories.index');
    Volt::route('categories/add', 'pages.categories.create')->name('pages.categories.create');
    Volt::route('categories/{category}', 'pages.categories.show')->name('pages.categories.show');
    Volt::route('categories/{category}/edit', 'pages.categories.edit')->name('pages.categories.edit');

    /*
    |--------------------------------------------------------------------------
    | Budgets Page
    |--------------------------------------------------------------------------
    */
    Volt::route('budgets', 'pages.budgets')->name('pages.budgets');

    /*
    |--------------------------------------------------------------------------
    | Settings Pages
    |--------------------------------------------------------------------------
    */
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Auth scaffolding (login, registration, etc.)
require __DIR__ . '/auth.php';
