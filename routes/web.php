<?php

use App\Http\Controllers\AccountingPeriod\AccountingPeriodController;
use App\Http\Controllers\Auth\Setup\AuthSetupNotReadyController;
use App\Http\Controllers\Auth\Setup\AuthSetupStepOneController;
use App\Http\Controllers\Auth\Setup\AuthSetupStepTwoController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Banner\BannerDissmissController;
use App\Http\Controllers\Client\ClientBillingController;
use App\Http\Controllers\Client\ClientCommercialController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Contractor\ContractorController;
use App\Http\Controllers\Contractor\ContractorEndsAtController;
use App\Http\Controllers\Contractor\Expense\Budget\ContractorExpenseBudgetController;
use App\Http\Controllers\Contractor\Expense\Charge\ContractorExpenseChargeController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Deal\BillingDealController;
use App\Http\Controllers\Deal\CommercialDealController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\EmployeeEndsAtController;
use App\Http\Controllers\Employee\Expense\Budget\EmployeeExpenseBudgetController;
use App\Http\Controllers\Employee\Expense\Charge\EmployeeExpenseChargeController;
use App\Http\Controllers\Expense\Budget\ExpenseBudgetController;
use App\Http\Controllers\Expense\Category\ExpenseCategoryController;
use App\Http\Controllers\Expense\Charge\ExpenseChargeController;
use App\Http\Controllers\Expense\Item\ExpenseItemController;
use App\Http\Controllers\Expense\SubCategory\ExpenseSubCategoryController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProjectDepartment\ProjectDepartmentController;
use App\Http\Controllers\Settings\AppearanceSettingsController;
use App\Http\Controllers\Settings\PasswordSettingsController;
use App\Http\Controllers\Settings\ProfileSettingsController;
use App\Http\Controllers\Settings\SecuritySettingsController;
use App\Http\Controllers\Team\TeamController;
use App\Http\Controllers\User\UserMemberController;
use App\Models\AccountingPeriod;
use App\Models\Client;
use App\Models\Comment;
use App\Models\Contractor;
use App\Models\Deal;
use App\Models\Employee;
use App\Models\ExpenseBudget;
use App\Models\ExpenseCategory;
use App\Models\ExpenseCharge;
use App\Models\ExpenseItem;
use App\Models\ExpenseSubCategory;
use App\Models\ProjectDepartment;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::post('/verification/code', [VerifyEmailController::class, 'code'])->name('verification.code')->middleware(['auth']);

Route::middleware(['auth', 'auth.include'])->group(function () {
    Route::prefix('/setup')->name('auth.setup.')->group(function () {
        Route::get('/not-ready', [AuthSetupNotReadyController::class, 'index'])->name('not-ready');
        Route::middleware(['auth.owner'])->group(function () {
            Route::prefix('/1')->name('step-one.')->controller(AuthSetupStepOneController::class)->group(function () {
                Route::get('/', 'edit')->name('edit');
                Route::post('/', 'update')->name('update');
            });
            Route::prefix('/2')->name('step-two.')->controller(AuthSetupStepTwoController::class)->group(function () {
                Route::get('/', 'edit')->name('edit');
                Route::post('/', 'update')->name('update');
            });
        });
    });
});

Route::middleware(['auth', 'auth.setup', 'auth.include', 'banner.include'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('index');

    Route::prefix('/banners')->name('banners.')->group(function () {
        Route::patch('/{banner}/dismiss', [BannerDissmissController::class, 'update'])->name('dismiss');
    });

    Route::prefix('/employees')->name('employees.')->controller(EmployeeController::class)->group(function () {
        Route::get('/', 'index')->name('index')->can('viewAny', Employee::class);
        Route::get('/create', 'create')->name('create')->can('viewAny', Employee::class);
        Route::post('/create', 'store')->name('store')->can('viewAny', Employee::class);
        Route::get('/edit/{employee}', 'edit')->name('edit')->withTrashed()->can('view', 'employee');
        Route::put('/edit/{employee}', 'update')->name('update')->withTrashed()->can('update', 'employee');
        Route::prefix('/edit/{employee}/ends-at')->name('ends-at.')->controller(EmployeeEndsAtController::class)->group(function () {
            Route::patch('/', 'update')->name('update')->withTrashed()->can('update', 'employee');
            Route::delete('/', 'destroy')->name('destroy')->withTrashed()->can('update', 'employee');
        });
        Route::delete('/trash/{employee?}', 'trash')->name('trash');
        Route::patch('/restore/{employee?}', 'restore')->name('restore');
        Route::delete('/delete/{employee?}', 'destroy')->name('delete');

        Route::prefix('/edit/{employee}')->name('expenses.')->group(function () {
            Route::prefix('/budgets')->name('budgets.')->controller(EmployeeExpenseBudgetController::class)->group(function () {
                Route::get('/', 'index')->name('index')->can('viewAny', ExpenseBudget::class);
                Route::get('/create', 'create')->name('create')->can('viewAny', ExpenseBudget::class);
                Route::post('/create', 'store')->name('store')->can('viewAny', ExpenseBudget::class);
                Route::get('/edit/{expenseBudget}', 'edit')->name('edit')->withTrashed()->can('view', 'expenseBudget');
                Route::put('/edit/{expenseBudget}', 'update')->name('update')->withTrashed()->can('update', 'expenseBudget');
                Route::delete('/trash/{expenseBudget?}', 'trash')->name('trash');
                Route::patch('/restore/{expenseBudget?}', 'restore')->name('restore');
                Route::delete('/delete/{expenseBudget?}', 'destroy')->name('delete');
            });
            Route::prefix('/charges')->name('charges.')->controller(EmployeeExpenseChargeController::class)->group(function () {
                Route::get('/', 'index')->name('index')->can('viewAny', ExpenseCharge::class);
                Route::get('/create', 'create')->name('create')->can('viewAny', ExpenseCharge::class);
                Route::post('/create', 'store')->name('store')->can('viewAny', ExpenseCharge::class);
                Route::get('/edit/{expenseCharge}', 'edit')->name('edit')->withTrashed()->can('view', 'expenseCharge');
                Route::put('/edit/{expenseCharge}', 'update')->name('update')->withTrashed()->can('update', 'expenseCharge');
                Route::delete('/trash/{expenseCharge?}', 'trash')->name('trash');
                Route::patch('/restore/{expenseCharge?}', 'restore')->name('restore');
                Route::delete('/delete/{expenseCharge?}', 'destroy')->name('delete');
            });
        });
    });

    Route::prefix('/contractors')->name('contractors.')->controller(ContractorController::class)->group(function () {
        Route::get('/', 'index')->name('index')->can('viewAny', Contractor::class);
        Route::get('/create', 'create')->name('create')->can('viewAny', Contractor::class);
        Route::post('/create', 'store')->name('store')->can('viewAny', Contractor::class);
        Route::get('/edit/{contractor}', 'edit')->name('edit')->withTrashed()->can('view', 'contractor');
        Route::put('/edit/{contractor}', 'update')->name('update')->withTrashed()->can('update', 'contractor');
        Route::prefix('/edit/{contractor}/ends-at')->name('ends-at.')->controller(ContractorEndsAtController::class)->group(function () {
            Route::patch('/', 'update')->name('update')->withTrashed()->can('update', 'contractor');
            Route::delete('/', 'destroy')->name('destroy')->withTrashed()->can('update', 'contractor');
        });
        Route::delete('/trash/{contractor?}', 'trash')->name('trash');
        Route::patch('/restore/{contractor?}', 'restore')->name('restore');
        Route::delete('/delete/{contractor?}', 'destroy')->name('delete');

        Route::prefix('/edit/{contractor}')->name('expenses.')->group(function () {
            Route::prefix('/budgets')->name('budgets.')->controller(ContractorExpenseBudgetController::class)->group(function () {
                Route::get('/', 'index')->name('index')->can('viewAny', ExpenseBudget::class);
                Route::get('/create', 'create')->name('create')->can('viewAny', ExpenseBudget::class);
                Route::post('/create', 'store')->name('store')->can('viewAny', ExpenseBudget::class);
                Route::get('/edit/{expenseBudget}', 'edit')->name('edit')->withTrashed()->can('view', 'expenseBudget');
                Route::put('/edit/{expenseBudget}', 'update')->name('update')->withTrashed()->can('update', 'expenseBudget');
                Route::delete('/trash/{expenseBudget?}', 'trash')->name('trash');
                Route::patch('/restore/{expenseBudget?}', 'restore')->name('restore');
                Route::delete('/delete/{expenseBudget?}', 'destroy')->name('delete');
            });
            Route::prefix('/charges')->name('charges.')->controller(ContractorExpenseChargeController::class)->group(function () {
                Route::get('/', 'index')->name('index')->can('viewAny', ExpenseCharge::class);
                Route::get('/create', 'create')->name('create')->can('viewAny', ExpenseCharge::class);
                Route::post('/create', 'store')->name('store')->can('viewAny', ExpenseCharge::class);
                Route::get('/edit/{expenseCharge}', 'edit')->name('edit')->withTrashed()->can('view', 'expenseCharge');
                Route::put('/edit/{expenseCharge}', 'update')->name('update')->withTrashed()->can('update', 'expenseCharge');
                Route::delete('/trash/{expenseCharge?}', 'trash')->name('trash');
                Route::patch('/restore/{expenseCharge?}', 'restore')->name('restore');
                Route::delete('/delete/{expenseCharge?}', 'destroy')->name('delete');
            });
        });
    });

    Route::prefix('/expenses')->name('expenses.')->group(function () {
        Route::prefix('/budgets')->name('budgets.')->controller(ExpenseBudgetController::class)->group(function () {
            Route::get('/', 'index')->name('index')->can('viewAny', ExpenseBudget::class);
            Route::get('/create', 'create')->name('create')->can('viewAny', ExpenseBudget::class);
            Route::post('/create', 'store')->name('store')->can('viewAny', ExpenseBudget::class);
            Route::get('/edit/{expenseBudget}', 'edit')->name('edit')->withTrashed()->can('view', 'expenseBudget');
            Route::put('/edit/{expenseBudget}', 'update')->name('update')->withTrashed()->can('update', 'expenseBudget');
            Route::delete('/trash/{expenseBudget?}', 'trash')->name('trash');
            Route::patch('/restore/{expenseBudget?}', 'restore')->name('restore');
            Route::delete('/delete/{expenseBudget?}', 'destroy')->name('delete');
        });
        Route::prefix('/charges')->name('charges.')->controller(ExpenseChargeController::class)->group(function () {
            Route::get('/', 'index')->name('index')->can('viewAny', ExpenseCharge::class);
            Route::get('/create', 'create')->name('create')->can('viewAny', ExpenseCharge::class);
            Route::post('/create', 'store')->name('store')->can('viewAny', ExpenseCharge::class);
            Route::get('/edit/{expenseCharge}', 'edit')->name('edit')->withTrashed()->can('view', 'expenseCharge');
            Route::put('/edit/{expenseCharge}', 'update')->name('update')->withTrashed()->can('update', 'expenseCharge');
            Route::delete('/trash/{expenseCharge?}', 'trash')->name('trash');
            Route::patch('/restore/{expenseCharge?}', 'restore')->name('restore');
            Route::delete('/delete/{expenseCharge?}', 'destroy')->name('delete');
        });
    });

    Route::prefix('/media')->name('media.')->controller(MediaController::class)->group(function () {
        Route::post('/{modelType}/{modelId}/{collection}', 'store')->name('store');
    });

    Route::prefix('/settings')->name('settings.')->group(function () {
        Route::redirect('/', '/settings/profile')->name('index');
        Route::prefix('/profile')->name('profile.')->controller(ProfileSettingsController::class)->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::patch('/', 'update')->name('update');
            Route::delete('/', 'destroy')->name('destroy');
        });
        Route::get('/security', [SecuritySettingsController::class, 'edit'])->name('security.edit');

        Route::prefix('/password')->name('password.')->controller(PasswordSettingsController::class)->group(function () {
            Route::patch('/', 'update')->name('update');
        });

        Route::get('/appearance', [AppearanceSettingsController::class, 'edit'])->name('appearance.edit');
    });

    Route::prefix('/teams')->name('teams.')->controller(TeamController::class)->group(function () {
        Route::get('/', 'index')->name('index')->can('viewAny', Team::class);
        Route::get('/create', 'create')->name('create')->can('create', Team::class);
        Route::post('/create', 'store')->name('store')->can('create', Team::class);
        Route::get('/edit/{team}', 'edit')->name('edit')->withTrashed()->can('view', 'team');
        Route::put('/edit/{team}', 'update')->name('update')->withTrashed()->can('update', 'team');
        Route::patch('/select/{team}', 'select')->name('select')->can('select', 'team');
        Route::delete('/trash/{team?}', 'trash')->name('trash');
        Route::patch('/restore/{team?}', 'restore')->name('restore');
        Route::delete('/delete/{team?}', 'destroy')->name('delete');

        Route::prefix('/edit/{team}/accounting-periods')->name('accounting-periods.')->controller(AccountingPeriodController::class)->scopeBindings()->group(function () {
            Route::get('/', 'index')->name('index')->can('viewAny', AccountingPeriod::class);
            Route::get('/create', 'create')->name('create')->can('create', AccountingPeriod::class);
            Route::post('/create', 'store')->name('store')->can('create', AccountingPeriod::class);
            Route::get('/edit/{accountingPeriod}', 'edit')->name('edit')->withTrashed()->can('view', 'accountingPeriod');
            Route::put('/edit/{accountingPeriod}', 'update')->name('update')->withTrashed()->can('update', 'accountingPeriod');
            Route::delete('/trash/{accountingPeriod?}', 'trash')->name('trash');
            Route::patch('/restore/{accountingPeriod?}', 'restore')->name('restore');
            Route::delete('/delete/{accountingPeriod?}', 'destroy')->name('delete');
        });

        Route::prefix('/edit/{team}/project-departments')->name('project-departments.')->controller(ProjectDepartmentController::class)->scopeBindings()->group(function () {
            Route::get('/', 'index')->name('index')->can('viewAny', ProjectDepartment::class);
            Route::get('/create', 'create')->name('create')->can('create', ProjectDepartment::class);
            Route::post('/create', 'store')->name('store')->can('create', ProjectDepartment::class);
            Route::get('/edit/{projectDepartment}', 'edit')->name('edit')->withTrashed()->can('view', 'projectDepartment');
            Route::put('/edit/{projectDepartment}', 'update')->name('update')->withTrashed()->can('update', 'projectDepartment');
            Route::delete('/trash/{projectDepartment?}', 'trash')->name('trash');
            Route::patch('/restore/{projectDepartment?}', 'restore')->name('restore');
            Route::delete('/delete/{projectDepartment?}', 'destroy')->name('delete');
        });

        Route::prefix('/edit/{team}/expenses/{expenseType}')->name('expenses.')->group(function () {
            Route::prefix('/categories')->name('categories.')->controller(ExpenseCategoryController::class)->group(function () {
                Route::get('/', 'index')->name('index')->can('viewAny', ExpenseCategory::class);
                Route::get('/create', 'create')->name('create')->can('create', ExpenseCategory::class);
                Route::post('/create', 'store')->name('store')->can('create', ExpenseCategory::class);
                Route::get('/edit/{expenseCategory}', 'edit')->name('edit')->withTrashed()->can('view', 'expenseCategory');
                Route::put('/edit/{expenseCategory}', 'update')->name('update')->withTrashed()->can('update', 'expenseCategory');
                Route::delete('/trash/{expenseCategory?}', 'trash')->name('trash');
                Route::patch('/restore/{expenseCategory?}', 'restore')->name('restore');
                Route::delete('/delete/{expenseCategory?}', 'destroy')->name('delete');
            });
            Route::prefix('/items')->name('items.')->controller(ExpenseItemController::class)->group(function () {
                Route::get('/', 'index')->name('index')->can('viewAny', ExpenseItem::class);
                Route::get('/create', 'create')->name('create')->can('create', ExpenseItem::class);
                Route::post('/create', 'store')->name('store')->can('create', ExpenseItem::class);
                Route::get('/edit/{expenseItem}', 'edit')->name('edit')->withTrashed()->can('view', 'expenseItem');
                Route::put('/edit/{expenseItem}', 'update')->name('update')->withTrashed()->can('update', 'expenseItem');
                Route::delete('/trash/{expenseItem?}', 'trash')->name('trash');
                Route::patch('/restore/{expenseItem?}', 'restore')->name('restore');
                Route::delete('/delete/{expenseItem?}', 'destroy')->name('delete');
            });
            Route::prefix('/sub-categories')->name('sub-categories.')->controller(ExpenseSubCategoryController::class)->group(function () {
                Route::get('/', 'index')->name('index')->can('viewAny', ExpenseSubCategory::class);
                Route::get('/create', 'create')->name('create')->can('create', ExpenseSubCategory::class);
                Route::post('/create', 'store')->name('store')->can('create', ExpenseSubCategory::class);
                Route::get('/edit/{expenseSubCategory}', 'edit')->name('edit')->withTrashed()->can('view', 'expenseSubCategory');
                Route::put('/edit/{expenseSubCategory}', 'update')->name('update')->withTrashed()->can('update', 'expenseSubCategory');
                Route::delete('/trash/{expenseSubCategory?}', 'trash')->name('trash');
                Route::patch('/restore/{expenseSubCategory?}', 'restore')->name('restore');
                Route::delete('/delete/{expenseSubCategory?}', 'destroy')->name('delete');
            });
        });

    });

    Route::name('users.')->group(function () {
        Route::prefix('/members')->name('members.')->controller(UserMemberController::class)->group(function () {
            Route::get('/', 'index')->name('index')->can('viewAny', User::class);
            Route::get('/create', 'create')->name('create')->can('create', User::class);
            Route::post('/create', 'store')->name('store')->can('create', User::class);
            Route::get('/edit/{member}', 'edit')->name('edit')->can('view', 'member');
            Route::put('/edit/{member}', 'update')->name('update')->can('update', 'member');
            Route::delete('/trash/{member?}', 'trash')->name('trash');
            Route::patch('/restore/{member?}', 'restore')->name('restore');
            Route::delete('/delete/{member?}', 'destroy')->name('delete');
        });
    });

    Route::prefix('/clients')->name('clients.')->controller(ClientController::class)->group(function () {
        Route::get('/', 'index')->name('index')->can('viewAny', Client::class);
        Route::get('/create', 'create')->name('create')->can('create', Client::class);
        Route::post('/create', 'store')->name('store')->can('create', Client::class);

        Route::get('/edit/{client}', 'edit')->name('edit')->can('view', 'client');
        Route::put('/edit/{client}', 'update')->name('update')->can('update', 'client');

        Route::delete('/trash/{client?}', 'trash')->name('trash');
        Route::patch('/restore/{client?}', 'restore')->name('restore');
        Route::delete('/delete/{client?}', 'destroy')->name('delete');

        Route::prefix('/{client}/commercials')->name('commercials.')->controller(ClientCommercialController::class)->group(function () {
            Route::get('/', 'index')->name('index');

            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('store');
            Route::get('/{deal}/edit', 'edit')->withTrashed()->name('edit');
            Route::put('/{deal}/edit', 'update')->name('update');

            Route::delete('/trash/{deal?}', 'trash')->name('trash');
            Route::patch('/restore/{deal?}', 'restore')->name('restore');
            Route::delete('/delete/{deal?}', 'destroy')->name('delete');

            Route::get('/validate/{deal}', 'validateDeal')->name('validate');
            Route::post('/validate/{deal}', 'processValidation')->name('validate.process');
        });

        Route::prefix('/{client}/billings')->name('billings.')->controller(ClientBillingController::class)->group(function () {
            Route::get('/', 'index')->name('index');

            Route::get('/{deal}/edit', 'edit')->withTrashed()->name('edit');
            Route::put('/{deal}/edit', 'update')->name('update');

            Route::delete('/trash/{deal?}', 'trash')->name('trash');
            Route::patch('/restore/{deal?}', 'restore')->name('restore');
            Route::delete('/delete/{deal?}', 'destroy')->name('delete');
        });
    });

    Route::prefix('deals')->group(function () {
        Route::prefix('commercial')->name('deals.commercials.')->controller(CommercialDealController::class)->group(function () {
            Route::get('/', 'index')->name('index')->can('viewAny', Deal::class);
            Route::get('/create', 'create')->name('create')->can('create', Deal::class);
            Route::post('/create', 'store')->name('store')->can('create', Deal::class);

            Route::get('/edit/{deal}', 'edit')->name('edit')->withTrashed()->can('view', 'deal');
            Route::put('/edit/{deal}', 'update')->name('update')->can('update', 'deal');

            Route::delete('/trash/{deal?}', 'trash')->name('trash');
            Route::patch('/restore/{deal?}', 'restore')->name('restore');
            Route::delete('/delete/{deal?}', 'destroy')->name('delete');

            Route::get('/validate/{deal}', 'validateDeal')->name('validate');
            Route::post('/validate/{deal}', 'processValidation')->name('validate.process');
        });

        Route::prefix('billing')->name('deals.billings.')->controller(BillingDealController::class)->group(function () {
            Route::get('/', 'index')->name('index')->can('viewAny', Deal::class);

            Route::get('/edit/{deal}', 'edit')->name('edit')->withTrashed()->can('view', 'deal');
            Route::put('/edit/{deal}', 'update')->name('update')->can('update', 'deal');

            Route::delete('/trash/{deal?}', 'trash')->name('trash');
            Route::patch('/restore/{deal?}', 'restore')->name('restore');
            Route::delete('/delete/{deal?}', 'destroy')->name('delete');
        });
    });

    Route::prefix('/comments')->name('comments.')->controller(CommentController::class)->group(function () {
        Route::post('/', 'store')->name('store')->can('create', Comment::class);
        Route::put('/{comment}', 'update')->name('update')->can('update', 'comment');
        Route::delete('/{comment}', 'destroy')->name('destroy')->can('delete', 'comment');
    });
});

require __DIR__.'/admin.php';
