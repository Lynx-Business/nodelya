<?php

namespace App\Http\Controllers\Employee\Expense\Charge;

use App\Data\Employee\Expense\Charge\Form\EmployeeExpenseChargeFormProps;
use App\Data\Employee\Expense\Charge\Index\EmployeeExpenseChargeIndexProps;
use App\Data\Expense\Charge\ExpenseChargeOneOrManyRequest;
use App\Data\Expense\Charge\ExpenseChargeResource;
use App\Data\Expense\Charge\Form\ExpenseChargeFormRequest;
use App\Data\Expense\Charge\Index\ExpenseChargeIndexRequest;
use App\Enums\Expense\ExpenseType;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ExpenseCharge;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class EmployeeExpenseChargeController extends Controller
{
    public function __construct(
        protected ExpenseType $type = ExpenseType::EMPLOYEE,
    ) {}

    public function index(Employee $employee, ExpenseChargeIndexRequest $data)
    {
        return Inertia::render('employees/expenses/charges/Index', EmployeeExpenseChargeIndexProps::from([
            'request'        => $data,
            'employee'       => $employee,
            'expenseCharges' => Lazy::inertia(
                fn () => ExpenseChargeResource::collect(
                    $employee->expenseCharges()
                        ->search($data->q)
                        ->when($data->trashed, fn (Builder $q) => $q->filterTrashed($data->trashed))
                        ->when(
                            $data->expense_category_ids || $data->expense_sub_category_ids || $data->expense_item_ids,
                            fn (Builder $q) => $q
                                ->whereHas(
                                    'expenseItem',
                                    fn (Builder $q) => $q
                                        ->when($data->expense_category_ids, fn (Builder $q) => $q->whereIntegerInRaw('expense_category_id', $data->expense_category_ids))
                                        ->when($data->expense_sub_category_ids, fn (Builder $q) => $q->whereIntegerInRaw('expense_sub_category_id', $data->expense_sub_category_ids))
                                        ->when($data->expense_item_ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->expense_item_ids)),
                                ),
                        )
                        ->when($data->accounting_period_id, fn (Builder $q) => $q->whereBelongsToAccountingPeriod($data->accounting_period_id))
                        ->orderBy($data->sort_by, $data->sort_direction)
                        ->with([
                            'expenseItem' => [
                                'expenseCategory',
                                'expenseSubCategory',
                            ],
                        ])
                        ->paginate(
                            perPage: $data->per_page ?? Config::integer('default.per_page'),
                            page: $data->page ?? 1,
                        )
                        ->withQueryString(),
                    PaginatedDataCollection::class,
                )->include('can_view', 'can_update', 'can_trash', 'can_restore', 'can_delete'),
            ),
            'trashedFilters'    => Lazy::inertia(fn () => TrashedFilter::labels()),
            'accountingPeriods' => Lazy::inertia(fn () => Services::accountingPeriod()->list()),
            'expenseCategories' => Lazy::inertia(
                fn () => Services::expense()->categoriesList(
                    fn (Builder $q) => $q
                        ->whereType($this->type),
                ),
            ),
            'expenseSubCategories' => Lazy::inertia(
                fn () => Services::expense()->subCategoriesList(
                    fn (Builder $q) => $q
                        ->whereType($this->type),
                ),
            ),
            'expenseItems' => Lazy::inertia(
                fn () => Services::expense()->itemsList(
                    fn (Builder $q) => $q
                        ->whereType($this->type),
                ),
            ),
        ]));
    }

    public function create(Employee $employee)
    {
        return Inertia::render('employees/expenses/charges/Create', EmployeeExpenseChargeFormProps::from([
            'expenseCategories' => Lazy::inertia(
                fn () => Services::expense()->categoriesList(
                    fn (Builder $q) => $q
                        ->whereType($this->type),
                ),
            ),
            'expenseSubCategories' => Lazy::inertia(
                fn () => Services::expense()->subCategoriesList(
                    fn (Builder $q) => $q
                        ->whereType($this->type),
                ),
            ),
            'expenseItems' => Lazy::inertia(
                fn () => Services::expense()->itemsList(
                    fn (Builder $q) => $q
                        ->whereType($this->type),
                ),
            ),
            'employee'         => $employee,
            'accountingPeriod' => Services::accountingPeriod()->current(),
        ]));
    }

    public function store(Employee $employee, ExpenseChargeFormRequest $data)
    {
        $expenseCharge = Services::expense()->createOrUpdateCharge->execute($data);

        if ($expenseCharge == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.charges.store.success'));

        return to_route('employees.expenses.charges.index', $employee);
    }

    public function show(Employee $employee, ExpenseCharge $expenseCharge)
    {
        //
    }

    public function edit(Employee $employee, ExpenseCharge $expenseCharge)
    {
        return Inertia::render('employees/expenses/charges/Edit', EmployeeExpenseChargeFormProps::from([
            'expenseCategories' => Lazy::inertia(
                fn () => Services::expense()->categoriesList(
                    fn (Builder $q) => $q
                        ->whereType($this->type),
                ),
            ),
            'expenseSubCategories' => Lazy::inertia(
                fn () => Services::expense()->subCategoriesList(
                    fn (Builder $q) => $q
                        ->whereType($this->type),
                ),
            ),
            'expenseItems' => Lazy::inertia(
                fn () => Services::expense()->itemsList(
                    fn (Builder $q) => $q
                        ->whereType($this->type),
                ),
            ),
            'employee'         => $employee,
            'accountingPeriod' => Services::accountingPeriod()->current(),
            'expenseCharge'    => $expenseCharge->load([
                'expenseItem' => [
                    'expenseCategory',
                    'expenseSubCategory',
                ],
            ]),
        ]));
    }

    public function update(Employee $employee, ExpenseCharge $expenseCharge, ExpenseChargeFormRequest $data)
    {
        $expenseCharge = Services::expense()->createOrUpdateCharge->execute($data);

        if ($expenseCharge == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.charges.update.success'));

        return to_route('employees.expenses.charges.index', $employee);
    }

    public function trash(Employee $employee, ExpenseChargeOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $employee->expenseCharges()
                ->when($data->expense_charge, fn (Builder $q) => $q->where('id', $data->expense_charge))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->delete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.expense.charges.trash.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function restore(Employee $employee, ExpenseChargeOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $employee->expenseCharges()
                ->onlyTrashed()
                ->when($data->expense_charge, fn (Builder $q) => $q->where('id', $data->expense_charge))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->restore();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.expense.charges.restore.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function destroy(Employee $employee, ExpenseChargeOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $employee->expenseCharges()
                ->withTrashed()
                ->when($data->expense_charge, fn (Builder $q) => $q->where('id', $data->expense_charge))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->forceDelete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.expense.charges.delete.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }
}
