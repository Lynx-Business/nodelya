<?php

namespace App\Http\Controllers\Expense\Item;

use App\Data\Expense\Item\ExpenseItemOneOrManyRequest;
use App\Data\Expense\Item\Form\ExpenseItemFormProps;
use App\Data\Expense\Item\Form\ExpenseItemFormRequest;
use App\Data\Expense\Item\Index\ExpenseItemIndexProps;
use App\Data\Expense\Item\Index\ExpenseItemIndexRequest;
use App\Data\Expense\Item\Index\ExpenseItemIndexResource;
use App\Data\Team\TeamListResource;
use App\Enums\Expense\ExpenseType;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\ExpenseItem;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class ExpenseItemController extends Controller
{
    public function index(Team $team, ExpenseType $expenseType, ExpenseItemIndexRequest $data)
    {
        return Inertia::render('teams/expenses/items/Index', ExpenseItemIndexProps::from([
            'request'      => $data,
            'team'         => TeamListResource::from($team),
            'expenseTypes' => Lazy::closure(fn () => ExpenseType::labels()),
            'expenseType'  => $expenseType,
            'expenseItems' => Lazy::inertia(
                fn () => ExpenseItemIndexResource::collect(
                    ExpenseItem::query()
                        ->whereBelongsToTeam($team)
                        ->whereType($expenseType)
                        ->search($data->q)
                        ->when($data->trashed, fn (Builder $q) => $q->filterTrashed($data->trashed))
                        ->when($data->expense_category_ids, fn (Builder $q) => $q->whereIntegerInRaw('expense_category_id', $data->expense_category_ids))
                        ->when($data->expense_sub_category_ids, fn (Builder $q) => $q->whereIntegerInRaw('expense_sub_category_id', $data->expense_sub_category_ids))
                        ->orderBy($data->sort_by, $data->sort_direction)
                        ->with([
                            'expenseCategory',
                            'expenseSubCategory',
                        ])
                        ->paginate(
                            perPage: $data->per_page ?? Config::integer('default.per_page'),
                            page: $data->page ?? 1,
                        )
                        ->withQueryString(),
                    PaginatedDataCollection::class,
                ),
            ),
            'expenseCategories' => Lazy::inertia(
                fn () => Services::expense()->categoriesList(
                    fn (Builder $q) => $q
                        ->whereBelongsToTeam($team)
                        ->whereType($expenseType),
                ),
            ),
            'expenseSubCategories' => Lazy::inertia(
                fn () => Services::expense()->subCategoriesList(
                    fn (Builder $q) => $q
                        ->whereBelongsToTeam($team)
                        ->whereType($expenseType)
                        ->when($data->expense_category_ids, fn (Builder $q) => $q->whereIntegerInRaw('expense_category_id', $data->expense_category_ids)),
                ),
            ),
            'trashedFilters' => Lazy::inertia(fn () => TrashedFilter::labels()),
        ]));
    }

    public function create(Team $team, ExpenseType $expenseType)
    {
        return Inertia::render('teams/expenses/items/Create', ExpenseItemFormProps::from([
            'team'              => $team,
            'expenseType'       => $expenseType,
            'expenseCategories' => Lazy::inertia(
                fn () => Services::expense()->categoriesList(
                    fn (Builder $q) => $q
                        ->whereBelongsToTeam($team)
                        ->whereType($expenseType),
                ),
            ),
            'expenseSubCategories' => Lazy::inertia(
                fn () => Services::expense()->subCategoriesList(
                    fn (Builder $q) => $q
                        ->whereBelongsToTeam($team)
                        ->whereType($expenseType),
                ),
            ),
        ]));
    }

    public function store(Team $team, ExpenseType $expenseType, ExpenseItemFormRequest $data)
    {
        $expenseItem = Services::expense()->createOrUpdateItem->execute($data);

        if ($expenseItem == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.items.store.success'));

        return to_route('teams.expenses.items.index', [
            'team'        => $team,
            'expenseType' => $expenseType,
        ]);
    }

    public function show(Team $team, ExpenseItem $expenseItem)
    {
        //
    }

    public function edit(Team $team, ExpenseType $expenseType, ExpenseItem $expenseItem)
    {
        return Inertia::render('teams/expenses/items/Edit', ExpenseItemFormProps::from([
            'team'              => $team,
            'expenseType'       => $expenseType,
            'expenseCategories' => Lazy::inertia(
                fn () => Services::expense()->categoriesList(
                    fn (Builder $q) => $q
                        ->whereBelongsToTeam($team)
                        ->whereType($expenseType),
                ),
            ),
            'expenseSubCategories' => Lazy::inertia(
                fn () => Services::expense()->subCategoriesList(
                    fn (Builder $q) => $q
                        ->whereBelongsToTeam($team)
                        ->whereType($expenseType),
                ),
            ),
            'expenseItem' => $expenseItem->load([
                'expenseCategory',
                'expenseSubCategory' => [
                    'expenseCategory',
                ],
            ]),
        ]));
    }

    public function update(Team $team, ExpenseType $expenseType, ExpenseItem $expenseItem, ExpenseItemFormRequest $data)
    {
        $expenseItem = Services::expense()->createOrUpdateItem->execute($data);

        if ($expenseItem == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.items.update.success'));

        return to_route('teams.expenses.items.index', [
            'team'        => $team,
            'expenseType' => $expenseType,
        ]);
    }

    public function trash(Team $team, ExpenseType $expenseType, ExpenseItemOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $team->expenseItems()
                ->whereType($expenseType)
                ->when($data->expense_item, fn (Builder $q) => $q->where('id', $data->expense_item))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->delete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.expense.items.trash.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function restore(Team $team, ExpenseType $expenseType, ExpenseItemOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $team->expenseItems()
                ->onlyTrashed()
                ->whereType($expenseType)
                ->when($data->expense_item, fn (Builder $q) => $q->where('id', $data->expense_item))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->restore();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.expense.items.restore.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function destroy(Team $team, ExpenseType $expenseType, ExpenseItemOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $team->expenseItems()
                ->withTrashed()
                ->whereType($expenseType)
                ->when($data->expense_item, fn (Builder $q) => $q->where('id', $data->expense_item))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->forceDelete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.expense.items.delete.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }
}
