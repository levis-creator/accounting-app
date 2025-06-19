<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::middleware(['auth'])->group(function () {
    Volt::route('dashboard', 'pages.dashboard')->name('pages.dashboard');
    Volt::route('accounts', 'pages.accounts')->name('pages.accounts');
    Volt::route('transactions', 'pages.transactions')->name('pages.transactions');
    Volt::route('categories', 'pages.categories')->name('pages.categories');
    Volt::route('budgets', 'pages.budgets')->name('pages.budgets');
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
