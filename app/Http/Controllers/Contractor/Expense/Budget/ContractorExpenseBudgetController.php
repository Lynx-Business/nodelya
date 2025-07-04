<?php

namespace App\Http\Controllers\Contractor\Expense\Budget;

use App\Data\Contractor\Expense\Budget\Form\ContractorExpenseBudgetFormProps;
use App\Data\Contractor\Expense\Budget\Index\ContractorExpenseBudgetIndexProps;
use App\Data\Expense\Budget\ExpenseBudgetOneOrManyRequest;
use App\Data\Expense\Budget\ExpenseBudgetResource;
use App\Data\Expense\Budget\Form\ExpenseBudgetFormRequest;
use App\Data\Expense\Budget\Index\ExpenseBudgetIndexRequest;
use App\Enums\Expense\ExpenseType;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Contractor;
use App\Models\ExpenseBudget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class ContractorExpenseBudgetController extends Controller
{
    public function __construct(
        protected ExpenseType $type = ExpenseType::CONTRACTOR,
    ) {}

    public function index(Contractor $contractor, ExpenseBudgetIndexRequest $data)
    {
        return Inertia::render('contractors/expenses/budgets/Index', ContractorExpenseBudgetIndexProps::from([
            'request'        => $data,
            'contractor'     => $contractor,
            'expenseBudgets' => Lazy::inertia(
                fn () => ExpenseBudgetResource::collect(
                    $contractor->expenseBudgets()
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
                        ->when($data->accounting_period_id, fn (Builder $q) => $q->whereInAccountingPeriod($data->accounting_period_id))
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

    public function create(Contractor $contractor)
    {
        return Inertia::render('contractors/expenses/budgets/Create', ContractorExpenseBudgetFormProps::from([
            'contractor'       => $contractor,
            'accountingPeriod' => Lazy::closure(
                fn () => Services::accountingPeriod()->current(),
            ),
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

    public function store(Contractor $contractor, ExpenseBudgetFormRequest $data)
    {
        $expenseBudget = Services::expense()->createOrUpdateBudget->execute($data);

        if ($expenseBudget == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.budgets.store.success'));

        return to_route('contractors.expenses.budgets.index', $contractor);
    }

    public function show(Contractor $contractor, ExpenseBudget $expenseBudget)
    {
        //
    }

    public function edit(Contractor $contractor, ExpenseBudget $expenseBudget)
    {
        return Inertia::render('contractors/expenses/budgets/Edit', ContractorExpenseBudgetFormProps::from([
            'contractor'       => $contractor,
            'accountingPeriod' => Lazy::closure(
                fn () => Services::accountingPeriod()->current(),
            ),
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
            'expenseBudget' => $expenseBudget->load([
                'expenseItem' => [
                    'expenseCategory',
                    'expenseSubCategory',
                ],
            ]),
        ]));
    }

    public function update(Contractor $contractor, ExpenseBudget $expenseBudget, ExpenseBudgetFormRequest $data)
    {
        $data->except('starts_at', 'ends_at');
        $expenseBudget = Services::expense()->createOrUpdateBudget->execute($data);

        if ($expenseBudget == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.budgets.update.success'));

        return to_route('contractors.expenses.budgets.index', $contractor);
    }

    public function trash(Contractor $contractor, ExpenseBudgetOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $contractor->expenseBudgets()
                ->when($data->expense_budget, fn (Builder $q) => $q->where('id', $data->expense_budget))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->delete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.expense.budgets.trash.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function restore(Contractor $contractor, ExpenseBudgetOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $contractor->expenseBudgets()
                ->onlyTrashed()
                ->when($data->expense_budget, fn (Builder $q) => $q->where('id', $data->expense_budget))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->restore();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.expense.budgets.restore.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function destroy(Contractor $contractor, ExpenseBudgetOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $contractor->expenseBudgets()
                ->withTrashed()
                ->when($data->expense_budget, fn (Builder $q) => $q->where('id', $data->expense_budget))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->forceDelete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.expense.budgets.delete.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }
}
