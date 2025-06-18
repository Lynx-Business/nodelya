<?php

namespace App\Http\Controllers\AccountingPeriod;

use App\Data\AccountingPeriod\AccountingPeriodOneOrManyRequest;
use App\Data\AccountingPeriod\Form\AccountingPeriodFormProps;
use App\Data\AccountingPeriod\Form\AccountingPeriodFormRequest;
use App\Data\AccountingPeriod\Index\AccountingPeriodIndexProps;
use App\Data\AccountingPeriod\Index\AccountingPeriodIndexRequest;
use App\Data\AccountingPeriod\Index\AccountingPeriodIndexResource;
use App\Data\Team\TeamListResource;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class AccountingPeriodController extends Controller
{
    public function index(Team $team, AccountingPeriodIndexRequest $data)
    {
        return Inertia::render('teams/accounting-periods/Index', AccountingPeriodIndexProps::from([
            'request'           => $data,
            'team'              => TeamListResource::from($team),
            'accountingPeriods' => Lazy::inertia(
                fn () => AccountingPeriodIndexResource::collect(
                    AccountingPeriod::query()
                        ->whereBelongsToTeam($team)
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

    public function create(Team $team)
    {
        return Inertia::render('teams/accounting-periods/Create', AccountingPeriodFormProps::from([
            'team' => $team,
        ]));
    }

    public function store(Team $team, AccountingPeriodFormRequest $data)
    {
        $accountingPeriod = Services::accountingPeriod()->createOrUpdate->execute($data);

        if ($accountingPeriod == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.accounting_periods.store.success'));

        return to_route('teams.accounting-periods.index', $team);
    }

    public function show(Team $team, AccountingPeriod $accountingPeriod)
    {
        //
    }

    public function edit(Team $team, AccountingPeriod $accountingPeriod)
    {
        return Inertia::render('teams/accounting-periods/Edit', AccountingPeriodFormProps::from([
            'team'             => $team,
            'accountingPeriod' => $accountingPeriod,
        ]));
    }

    public function update(Team $team, AccountingPeriod $accountingPeriod, AccountingPeriodFormRequest $data)
    {
        $accountingPeriod = Services::accountingPeriod()->createOrUpdate->execute($data);

        if ($accountingPeriod == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.accounting_periods.update.success'));

        return to_route('teams.accounting-periods.index', $team);
    }

    public function trash(Team $team, AccountingPeriodOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $team->accountingPeriods()
                ->when($data->accounting_period, fn (Builder $q) => $q->where('id', $data->accounting_period))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->delete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.accounting_periods.trash.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function restore(Team $team, AccountingPeriodOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $team->accountingPeriods()
                ->onlyTrashed()
                ->when($data->accounting_period, fn (Builder $q) => $q->where('id', $data->accounting_period))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->restore();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.accounting_periods.restore.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function destroy(Team $team, AccountingPeriodOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $team->accountingPeriods()
                ->withTrashed()
                ->when($data->accounting_period, fn (Builder $q) => $q->where('id', $data->accounting_period))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->forceDelete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.accounting_periods.delete.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }
}
