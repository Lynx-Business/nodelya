<?php

namespace App\Http\Controllers\Expense\Budget;

use App\Data\Expense\Budget\ExpenseBudgetOneOrManyRequest;
use App\Data\Expense\Budget\ExpenseBudgetResource;
use App\Data\Expense\Budget\Form\ExpenseBudgetFormProps;
use App\Data\Expense\Budget\Form\ExpenseBudgetFormRequest;
use App\Data\Expense\Budget\Index\ExpenseBudgetIndexProps;
use App\Data\Expense\Budget\Index\ExpenseBudgetIndexRequest;
use App\Enums\Expense\ExpenseType;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\ExpenseBudget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class ExpenseBudgetController extends Controller
{
    public function __construct(
        protected ExpenseType $type = ExpenseType::GENERAL,
    ) {}

    public function index(ExpenseBudgetIndexRequest $data)
    {
        return Inertia::render('expenses/budgets/Index', ExpenseBudgetIndexProps::from([
            'request'        => $data,
            'expenseBudgets' => Lazy::inertia(
                fn () => ExpenseBudgetResource::collect(
                    ExpenseBudget::query()
                        ->whereType($this->type)
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
                        ->when($data->starts_at, fn (Builder $q) => $q->where('starts_at', '>=', $data->starts_at))
                        ->when($data->ends_at, fn (Builder $q) => $q->where('ends_at', '<=', $data->ends_at))
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
            'trashedFilters' => Lazy::inertia(fn () => TrashedFilter::labels()),
        ]));
    }

    public function create()
    {
        return Inertia::render('expenses/budgets/Create', ExpenseBudgetFormProps::from([
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

    public function store(ExpenseBudgetFormRequest $data)
    {
        $expenseBudget = Services::expense()->createOrUpdateBudget->execute($data);

        if ($expenseBudget == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.budgets.store.success'));

        return to_route('expenses.budgets.index');
    }

    public function show(ExpenseBudget $expenseBudget)
    {
        //
    }

    public function edit(ExpenseBudget $expenseBudget)
    {
        return Inertia::render('expenses/budgets/Edit', ExpenseBudgetFormProps::from([
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

    public function update(ExpenseBudget $expenseBudget, ExpenseBudgetFormRequest $data)
    {
        $expenseBudget = Services::expense()->createOrUpdateBudget->execute($data);

        if ($expenseBudget == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.budgets.update.success'));

        return to_route('expenses.budgets.index');
    }

    public function trash(ExpenseBudgetOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = ExpenseBudget::query()
                ->whereType($this->type)
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

    public function restore(ExpenseBudgetOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = ExpenseBudget::query()
                ->onlyTrashed()
                ->whereType($this->type)
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

    public function destroy(ExpenseBudgetOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = ExpenseBudget::query()
                ->withTrashed()
                ->whereType($this->type)
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
