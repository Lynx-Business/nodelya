<?php

namespace App\Http\Controllers\Contractor\Expense\Charge;

use App\Data\Contractor\Expense\Charge\Form\ContractorExpenseChargeFormProps;
use App\Data\Contractor\Expense\Charge\Index\ContractorExpenseChargeIndexProps;
use App\Data\Expense\Charge\ExpenseChargeOneOrManyRequest;
use App\Data\Expense\Charge\ExpenseChargeResource;
use App\Data\Expense\Charge\Form\ExpenseChargeFormRequest;
use App\Data\Expense\Charge\Index\ExpenseChargeIndexRequest;
use App\Enums\Expense\ExpenseType;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Contractor;
use App\Models\ExpenseCharge;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class ContractorExpenseChargeController extends Controller
{
    public function __construct(
        protected ExpenseType $type = ExpenseType::CONTRACTOR,
    ) {}

    public function index(Contractor $contractor, ExpenseChargeIndexRequest $data)
    {
        return Inertia::render('contractors/expenses/charges/Index', ContractorExpenseChargeIndexProps::from([
            'request'        => $data,
            'contractor'     => $contractor,
            'expenseCharges' => Lazy::inertia(
                fn () => ExpenseChargeResource::collect(
                    $contractor->expenseCharges()
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
        return Inertia::render('contractors/expenses/charges/Create', ContractorExpenseChargeFormProps::from([
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

    public function store(Contractor $contractor, ExpenseChargeFormRequest $data)
    {
        $expenseCharge = Services::expense()->createOrUpdateCharge->execute($data);

        if ($expenseCharge == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.charges.store.success'));

        return to_route('contractors.expenses.charges.index', $contractor);
    }

    public function show(Contractor $contractor, ExpenseCharge $expenseCharge)
    {
        //
    }

    public function edit(Contractor $contractor, ExpenseCharge $expenseCharge)
    {
        return Inertia::render('contractors/expenses/charges/Edit', ContractorExpenseChargeFormProps::from([
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
            'expenseCharge' => $expenseCharge->load([
                'expenseItem' => [
                    'expenseCategory',
                    'expenseSubCategory',
                ],
            ]),
        ]));
    }

    public function update(Contractor $contractor, ExpenseCharge $expenseCharge, ExpenseChargeFormRequest $data)
    {
        $expenseCharge = Services::expense()->createOrUpdateCharge->execute($data);

        if ($expenseCharge == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.charges.update.success'));

        return to_route('contractors.expenses.charges.index', $contractor);
    }

    public function trash(Contractor $contractor, ExpenseChargeOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $contractor->expenseCharges()
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

    public function restore(Contractor $contractor, ExpenseChargeOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $contractor->expenseCharges()
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

    public function destroy(Contractor $contractor, ExpenseChargeOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $contractor->expenseCharges()
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
