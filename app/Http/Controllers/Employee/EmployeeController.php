<?php

namespace App\Http\Controllers\Employee;

use App\Data\Employee\EmployeeOneOrManyRequest;
use App\Data\Employee\EmployeeResource;
use App\Data\Employee\Form\EmployeeFormProps;
use App\Data\Employee\Form\EmployeeFormRequest;
use App\Data\Employee\Index\EmployeeIndexProps;
use App\Data\Employee\Index\EmployeeIndexRequest;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class EmployeeController extends Controller
{
    public function index(EmployeeIndexRequest $data)
    {
        return Inertia::render('employees/Index', EmployeeIndexProps::from([
            'request'   => $data,
            'employees' => Lazy::inertia(
                fn () => EmployeeResource::collect(
                    Employee::query()
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
        return Inertia::render('employees/Create', EmployeeFormProps::from([
            'projectDepartments' => Lazy::inertia(
                fn () => Services::projectDepartment()->list(),
            ),
        ]));
    }

    public function store(EmployeeFormRequest $data)
    {
        $employee = Services::employee()->createOrUpdate->execute($data);

        if ($employee == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.employees.store.success'));

        return to_route('employees.index');
    }

    public function show(Employee $employee)
    {
        //
    }

    public function edit(Employee $employee)
    {
        return Inertia::render('employees/Edit', EmployeeFormProps::from([
            'projectDepartments' => Lazy::inertia(
                fn () => Services::projectDepartment()->list(),
            ),
            'employee' => $employee->load([
                'projectDepartment',
            ]),
        ]));
    }

    public function update(Employee $employee, EmployeeFormRequest $data)
    {
        $employee = Services::employee()->createOrUpdate->execute($data);

        if ($employee == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.employees.update.success'));

        return back();
    }

    public function trash(EmployeeOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = Employee::query()
                ->when($data->employee, fn (Builder $q) => $q->where('id', $data->employee))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->delete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.employees.trash.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function restore(EmployeeOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = Employee::query()
                ->onlyTrashed()
                ->when($data->employee, fn (Builder $q) => $q->where('id', $data->employee))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->restore();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.employees.restore.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function destroy(EmployeeOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = Employee::query()
                ->withTrashed()
                ->when($data->employee, fn (Builder $q) => $q->where('id', $data->employee))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->forceDelete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.employees.delete.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }
}
