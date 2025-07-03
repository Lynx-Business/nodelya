<?php

namespace App\Http\Controllers\Expense\SubCategory;

use App\Data\Expense\SubCategory\ExpenseSubCategoryOneOrManyRequest;
use App\Data\Expense\SubCategory\ExpenseSubCategoryResource;
use App\Data\Expense\SubCategory\Form\ExpenseSubCategoryFormProps;
use App\Data\Expense\SubCategory\Form\ExpenseSubCategoryFormRequest;
use App\Data\Expense\SubCategory\Index\ExpenseSubCategoryIndexProps;
use App\Data\Expense\SubCategory\Index\ExpenseSubCategoryIndexRequest;
use App\Data\Team\TeamListResource;
use App\Enums\Expense\ExpenseType;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\ExpenseSubCategory;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class ExpenseSubCategoryController extends Controller
{
    public function index(Team $team, ExpenseType $expenseType, ExpenseSubCategoryIndexRequest $data)
    {
        return Inertia::render('teams/expenses/sub-categories/Index', ExpenseSubCategoryIndexProps::from([
            'request'              => $data,
            'team'                 => TeamListResource::from($team),
            'expenseTypes'         => Lazy::closure(fn () => ExpenseType::labels()),
            'expenseType'          => $expenseType,
            'expenseSubCategories' => Lazy::inertia(
                fn () => ExpenseSubCategoryResource::collect(
                    ExpenseSubCategory::query()
                        ->whereType($expenseType)
                        ->search($data->q)
                        ->when($data->trashed, fn (Builder $q) => $q->filterTrashed($data->trashed))
                        ->when($data->expense_category_ids, fn (Builder $q) => $q->whereIntegerInRaw('expense_category_id', $data->expense_category_ids))
                        ->orderBy($data->sort_by, $data->sort_direction)
                        ->with([
                            'expenseCategory',
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
            'expenseCategories' => Lazy::inertia(
                fn () => Services::expense()->categoriesList(
                    fn (Builder $q) => $q
                        ->whereType($expenseType),
                ),
            ),
        ]));
    }

    public function create(Team $team, ExpenseType $expenseType)
    {
        return Inertia::render('teams/expenses/sub-categories/Create', ExpenseSubCategoryFormProps::from([
            'team'              => $team,
            'expenseType'       => $expenseType,
            'expenseCategories' => Lazy::inertia(
                fn () => Services::expense()->categoriesList(
                    fn (Builder $q) => $q
                        ->whereType($expenseType),
                ),
            ),
        ]));
    }

    public function store(Team $team, ExpenseType $expenseType, ExpenseSubCategoryFormRequest $data)
    {
        $expenseSubCategory = Services::expense()->createOrUpdateSubCategory->execute($data);

        if ($expenseSubCategory == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.sub_categories.store.success'));

        return to_route('teams.expenses.sub-categories.index', [
            'team'        => $team,
            'expenseType' => $expenseType,
        ]);
    }

    public function show(Team $team, ExpenseSubCategory $expenseSubCategory)
    {
        //
    }

    public function edit(Team $team, ExpenseType $expenseType, ExpenseSubCategory $expenseSubCategory)
    {
        return Inertia::render('teams/expenses/sub-categories/Edit', ExpenseSubCategoryFormProps::from([
            'team'              => $team,
            'expenseType'       => $expenseType,
            'expenseCategories' => Lazy::inertia(
                fn () => Services::expense()->categoriesList(
                    fn (Builder $q) => $q
                        ->whereType($expenseType),
                ),
            ),
            'expenseSubCategory' => $expenseSubCategory->load([
                'expenseCategory',
            ]),
        ]));
    }

    public function update(Team $team, ExpenseType $expenseType, ExpenseSubCategory $expenseSubCategory, ExpenseSubCategoryFormRequest $data)
    {
        $expenseSubCategory = Services::expense()->createOrUpdateSubCategory->execute($data);

        if ($expenseSubCategory == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.expense.sub_categories.update.success'));

        return to_route('teams.expenses.sub-categories.index', [
            'team'        => $team,
            'expenseType' => $expenseType,
        ]);
    }

    public function trash(Team $team, ExpenseType $expenseType, ExpenseSubCategoryOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $team->expenseSubCategories()
                ->whereType($expenseType)
                ->when($data->expense_sub_category, fn (Builder $q) => $q->where('id', $data->expense_sub_category))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->delete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.expense.sub_categories.trash.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function restore(Team $team, ExpenseType $expenseType, ExpenseSubCategoryOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $team->expenseSubCategories()
                ->whereType($expenseType)
                ->onlyTrashed()
                ->when($data->expense_sub_category, fn (Builder $q) => $q->where('id', $data->expense_sub_category))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->restore();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.expense.sub_categories.restore.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function destroy(Team $team, ExpenseType $expenseType, ExpenseSubCategoryOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $team->expenseSubCategories()
                ->whereType($expenseType)
                ->withTrashed()
                ->when($data->expense_sub_category, fn (Builder $q) => $q->where('id', $data->expense_sub_category))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->forceDelete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.expense.sub_categories.delete.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }
}
