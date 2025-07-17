<?php

namespace App\Http\Controllers\Expense\Management;

use App\Data\Expense\Management\Index\ExpenseManagementIndexProps;
use App\Data\Expense\Management\Index\ExpenseManagementIndexRequest;
use App\Data\Expense\Management\Index\ExpenseManagementTypeResource;
use App\Enums\Expense\ExpenseType;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;

class ExpenseManagementController extends Controller
{
    public function index(ExpenseManagementIndexRequest $data)
    {
        $accountingPeriod = $data->accounting_period_model;

        return Inertia::render('expenses/management/Index', ExpenseManagementIndexProps::from([
            'request'            => $data,
            'accountingPeriods'  => Lazy::inertia(fn () => Services::accountingPeriod()->list()),
            'expenseTypes'       => Lazy::closure(fn () => ExpenseType::labels()),
            'projectDepartments' => Lazy::inertia(fn () => Services::projectDepartment()->list()),
            'generalRow'         => Lazy::closure(
                fn () => new ExpenseManagementTypeResource(
                    type             : ExpenseType::GENERAL,
                    accountingPeriod : $accountingPeriod,
                ),
            ),
            'employeeRow' => Lazy::closure(
                fn () => new ExpenseManagementTypeResource(
                    type             : ExpenseType::EMPLOYEE,
                    accountingPeriod : $accountingPeriod,
                ),
            ),
            'contractorRow' => Lazy::closure(
                fn () => new ExpenseManagementTypeResource(
                    type             : ExpenseType::CONTRACTOR,
                    accountingPeriod : $accountingPeriod,
                ),
            ),
        ]));
    }
}
