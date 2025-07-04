<?php

namespace App\Http\Controllers\Contractor;

use App\Data\Contractor\ContractorOneOrManyRequest;
use App\Data\Contractor\ContractorResource;
use App\Data\Contractor\Form\ContractorFormProps;
use App\Data\Contractor\Form\ContractorFormRequest;
use App\Data\Contractor\Index\ContractorIndexProps;
use App\Data\Contractor\Index\ContractorIndexRequest;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Contractor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class ContractorController extends Controller
{
    public function index(ContractorIndexRequest $data)
    {
        return Inertia::render('contractors/Index', ContractorIndexProps::from([
            'request'     => $data,
            'contractors' => Lazy::inertia(
                fn () => ContractorResource::collect(
                    Contractor::query()
                        ->search($data->q)
                        ->when($data->trashed, fn (Builder $q) => $q->filterTrashed($data->trashed))
                        ->when($data->accounting_period_id, fn (Builder $q) => $q->whereInAccountingPeriod($data->accounting_period_id))
                        ->when($data->project_department_ids, fn (Builder $q) => $q->whereIntegerInRaw('project_department_id', $data->project_department_ids))
                        ->orderBy($data->sort_by, $data->sort_direction)
                        ->with([
                            'projectDepartment',
                        ])
                        ->paginate(
                            perPage: $data->per_page ?? Config::integer('default.per_page'),
                            page: $data->page ?? 1,
                        )
                        ->withQueryString(),
                    PaginatedDataCollection::class,
                )->include('can_view', 'can_update', 'can_trash', 'can_restore', 'can_delete'),
            ),
            'trashedFilters'     => Lazy::inertia(fn () => TrashedFilter::labels()),
            'accountingPeriods'  => Lazy::inertia(fn () => Services::accountingPeriod()->list()),
            'projectDepartments' => Lazy::inertia(fn () => Services::projectDepartment()->list()),
        ]));
    }

    public function create()
    {
        return Inertia::render('contractors/Create', ContractorFormProps::from([
            'projectDepartments' => Lazy::inertia(
                fn () => Services::projectDepartment()->list(),
            ),
        ]));
    }

    public function store(ContractorFormRequest $data)
    {
        $contractor = Services::contractor()->createOrUpdate->execute($data);

        if ($contractor == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.contractors.store.success'));

        return to_route('contractors.index');
    }

    public function show(Contractor $contractor)
    {
        //
    }

    public function edit(Contractor $contractor)
    {
        return Inertia::render('contractors/Edit', ContractorFormProps::from([
            'projectDepartments' => Lazy::inertia(
                fn () => Services::projectDepartment()->list(),
            ),
            'contractor' => $contractor->load([
                'projectDepartment',
            ]),
        ]));
    }

    public function update(Contractor $contractor, ContractorFormRequest $data)
    {
        $contractor = Services::contractor()->createOrUpdate->execute($data);

        if ($contractor == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.contractors.update.success'));

        return back();
    }

    public function trash(ContractorOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = Contractor::query()
                ->when($data->contractor, fn (Builder $q) => $q->where('id', $data->contractor))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->delete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.contractors.trash.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function restore(ContractorOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = Contractor::query()
                ->onlyTrashed()
                ->when($data->contractor, fn (Builder $q) => $q->where('id', $data->contractor))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->restore();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.contractors.restore.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function destroy(ContractorOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = Contractor::query()
                ->withTrashed()
                ->when($data->contractor, fn (Builder $q) => $q->where('id', $data->contractor))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->forceDelete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.contractors.delete.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }
}
