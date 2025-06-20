<?php

namespace App\Http\Controllers\Expense\Category;

use App\Data\Expense\Category\ExpenseCategoryOneOrManyRequest;
use App\Data\Expense\Category\Form\ExpenseCategoryFormProps;
use App\Data\Expense\Category\Form\ExpenseCategoryFormRequest;
use App\Data\Expense\Category\Index\ExpenseCategoryIndexProps;
use App\Data\Expense\Category\Index\ExpenseCategoryIndexRequest;
use App\Data\Expense\Category\Index\ExpenseCategoryIndexResource;
use App\Data\Team\TeamListResource;
use App\Enums\Expense\ExpenseType;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class ExpenseCategoryController extends Controller
{
    public function index(Team $team, ExpenseType $expenseType, ExpenseCategoryIndexRequest $data)
    {
        return Inertia::render('teams/expenses/categories/Index', ExpenseCategoryIndexProps::from([
            'request'           => $data,
            'team'              => TeamListResource::from($team),
            'expenseTypes'      => Lazy::closure(fn () => ExpenseType::labels()),
            'expenseType'       => $expenseType,
            'expenseCategories' => Lazy::inertia(
                fn () => ExpenseCategoryIndexResource::collect(
                    ExpenseCategory::query()
                        ->whereBelongsToTeam($team)
                        ->whereType($expenseType)
                        ->search($data->q)
                        ->when($data->trashed, fn (Builder $q) => $q->filterTrashed($data->trashed))
                        ->orderBy($data->sort_by, $data->sort_direction)
                        ->paginate(
                            perPage: $data->per_page ?? Config::integer('default.per_page'),
                            page: $data->page ?? 1,
                        )
                        ->withQueryString(),
                    PaginatedDataCollection::class,
                ),
            ),
            'trashed_filters' => Lazy::inertia(fn () => TrashedFilter::labels()),
        ]));
    }

    public function create(Team $team, ExpenseType $expenseType)
    {
        return Inertia::render('teams/expenses/categories/Create', ExpenseCategoryFormProps::from([
            'team'        => $team,
            'expenseType' => $expenseType,
        ]));
    }

    public function store(Team $team, ExpenseType $expenseType, ExpenseCategoryFormRequest $data)
    {
        $expenseCategory = Services::expense()->createOrUpdateCategory->execute($data);

        if ($expenseCategory == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.categories.store.success'));

        return to_route('teams.expenses.categories.index', [
            'team'        => $team,
            'expenseType' => $expenseType,
        ]);
    }

    public function show(Team $team, ExpenseCategory $expenseCategory)
    {
        //
    }

    public function edit(Team $team, ExpenseType $expenseType, ExpenseCategory $expenseCategory)
    {
        return Inertia::render('teams/expenses/categories/Edit', ExpenseCategoryFormProps::from([
            'team'            => $team,
            'expenseType'     => $expenseType,
            'expenseCategory' => $expenseCategory,
        ]));
    }

    public function update(Team $team, ExpenseType $expenseType, ExpenseCategory $expenseCategory, ExpenseCategoryFormRequest $data)
    {
        $expenseCategory = Services::expense()->createOrUpdateCategory->execute($data);

        if ($expenseCategory == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.categories.update.success'));

        return to_route('teams.expenses.categories.index', [
            'team'        => $team,
            'expenseType' => $expenseType,
        ]);
    }

    public function trash(Team $team, ExpenseType $expenseType, ExpenseCategoryOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $team->expenseCategories()
                ->whereType($expenseType)
                ->when($data->expense_category, fn (Builder $q) => $q->where('id', $data->expense_category))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->delete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.expense.categories.trash.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function restore(Team $team, ExpenseType $expenseType, ExpenseCategoryOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $team->expenseCategories()
                ->onlyTrashed()
                ->whereType($expenseType)
                ->when($data->expense_category, fn (Builder $q) => $q->where('id', $data->expense_category))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->restore();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.expense.categories.restore.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function destroy(Team $team, ExpenseType $expenseType, ExpenseCategoryOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $team->expenseCategories()
                ->withTrashed()
                ->whereType($expenseType)
                ->when($data->expense_category, fn (Builder $q) => $q->where('id', $data->expense_category))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->forceDelete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.expense.categories.delete.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }
}
