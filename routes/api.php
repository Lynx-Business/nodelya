<?php

use App\Http\Controllers\Expense\Management\Api\ExpenseManagementApiBudgetController;
use App\Http\Controllers\Expense\Management\Api\ExpenseManagementApiChargeController;
use App\Http\Controllers\Expense\Management\Api\ExpenseManagementApiContractorController;
use App\Http\Controllers\Expense\Management\Api\ExpenseManagementApiEmployeeController;
use App\Models\ExpenseCharge;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->name('api.')->middleware(['auth', 'auth.setup'])->group(function () {
    Route::prefix('/expenses')->name('expenses.')->group(function () {
        Route::prefix('/management')->name('management.')->group(function () {
            Route::prefix('/budgets')->name('budgets.')->controller(ExpenseManagementApiBudgetController::class)->group(function () {
                Route::get('/', 'index')->name('index');
            });
            Route::prefix('/charges')->name('charges.')->controller(ExpenseManagementApiChargeController::class)->group(function () {
                Route::post('/store', 'store')->name('store')->can('create', ExpenseCharge::class);
                Route::put('/edit/{expenseCharge}', 'update')->name('update')->can('update', 'expenseCharge');
            });
            Route::prefix('/employees')->name('employees.')->controller(ExpenseManagementApiEmployeeController::class)->group(function () {
                Route::get('/', 'index')->name('index');
            });
            Route::prefix('/contractors')->name('contractors.')->controller(ExpenseManagementApiContractorController::class)->group(function () {
                Route::get('/', 'index')->name('index');
            });
        });
    });
});
