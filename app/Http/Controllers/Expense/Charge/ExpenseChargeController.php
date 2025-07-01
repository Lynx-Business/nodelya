<?php

namespace App\Http\Controllers\Expense\Charge;

use App\Data\Expense\Charge\ExpenseChargeOneOrManyRequest;
use App\Data\Expense\Charge\ExpenseChargeResource;
use App\Data\Expense\Charge\Form\ExpenseChargeFormProps;
use App\Data\Expense\Charge\Form\ExpenseChargeFormRequest;
use App\Data\Expense\Charge\Index\ExpenseChargeIndexProps;
use App\Data\Expense\Charge\Index\ExpenseChargeIndexRequest;
use App\Enums\Expense\ExpenseType;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\ExpenseCharge;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class ExpenseChargeController extends Controller
{
    public function __construct(
        protected ExpenseType $type = ExpenseType::GENERAL,
    ) {}

    public function index(ExpenseChargeIndexRequest $data)
    {
        return Inertia::render('expenses/charges/Index', ExpenseChargeIndexProps::from([
            'request'        => $data,
            'expenseCharges' => Lazy::inertia(
                fn () => ExpenseChargeResource::collect(
                    ExpenseCharge::query()
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
        return Inertia::render('expenses/charges/Create', ExpenseChargeFormProps::from([
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

    public function store(ExpenseChargeFormRequest $data)
    {
        $expenseCharge = Services::expense()->createOrUpdateCharge->execute($data);

        if ($expenseCharge == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.charges.store.success'));

        return to_route('expenses.charges.index');
    }

    public function show(ExpenseCharge $expenseCharge)
    {
        //
    }

    public function edit(ExpenseCharge $expenseCharge)
    {
        return Inertia::render('expenses/charges/Edit', ExpenseChargeFormProps::from([
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

    public function update(ExpenseCharge $expenseCharge, ExpenseChargeFormRequest $data)
    {
        $expenseCharge = Services::expense()->createOrUpdateCharge->execute($data);

        if ($expenseCharge == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.charges.update.success'));

        return to_route('expenses.charges.index');
    }

    public function trash(ExpenseChargeOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = ExpenseCharge::query()
                ->whereType($this->type)
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

    public function restore(ExpenseChargeOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = ExpenseCharge::query()
                ->onlyTrashed()
                ->whereType($this->type)
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

    public function destroy(ExpenseChargeOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = ExpenseCharge::query()
                ->withTrashed()
                ->whereType($this->type)
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
