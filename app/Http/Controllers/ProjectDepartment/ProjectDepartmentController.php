<?php

namespace App\Http\Controllers\ProjectDepartment;

use App\Data\ProjectDepartment\Form\ProjectDepartmentFormProps;
use App\Data\ProjectDepartment\Form\ProjectDepartmentFormRequest;
use App\Data\ProjectDepartment\Index\ProjectDepartmentIndexProps;
use App\Data\ProjectDepartment\Index\ProjectDepartmentIndexRequest;
use App\Data\ProjectDepartment\Index\ProjectDepartmentIndexResource;
use App\Data\ProjectDepartment\ProjectDepartmentOneOrManyRequest;
use App\Data\Team\TeamListResource;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\ProjectDepartment;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;

class ProjectDepartmentController extends Controller
{
    public function index(Team $team, ProjectDepartmentIndexRequest $data)
    {
        return Inertia::render('teams/project-departments/Index', ProjectDepartmentIndexProps::from([
            'request'            => $data,
            'team'               => TeamListResource::from($team),
            'projectDepartments' => Lazy::inertia(
                fn () => ProjectDepartmentIndexResource::collect(
                    ProjectDepartment::query()
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
            'trashedFilters' => Lazy::inertia(fn () => TrashedFilter::labels()),
        ]));
    }

    public function create(Team $team)
    {
        return Inertia::render('teams/project-departments/Create', ProjectDepartmentFormProps::from([
            'team' => $team,
        ]));
    }

    public function store(Team $team, ProjectDepartmentFormRequest $data)
    {
        $projectDepartment = Services::projectDepartment()->createOrUpdate->execute($data);

        if ($projectDepartment == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.project_departments.store.success'));

        return to_route('teams.project-departments.index', $team);
    }

    public function show(Team $team, ProjectDepartment $projectDepartment)
    {
        //
    }

    public function edit(Team $team, ProjectDepartment $projectDepartment)
    {
        return Inertia::render('teams/project-departments/Edit', ProjectDepartmentFormProps::from([
            'team'              => $team,
            'projectDepartment' => $projectDepartment,
        ]));
    }

    public function update(Team $team, ProjectDepartment $projectDepartment, ProjectDepartmentFormRequest $data)
    {
        $projectDepartment = Services::projectDepartment()->createOrUpdate->execute($data);

        if ($projectDepartment == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.project_departments.update.success'));

        return to_route('teams.project-departments.index', $team);
    }

    public function trash(Team $team, ProjectDepartmentOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $team->projectDepartments()
                ->when($data->project_department, fn (Builder $q) => $q->where('id', $data->project_department))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->delete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.project_departments.trash.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function restore(Team $team, ProjectDepartmentOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $team->projectDepartments()
                ->onlyTrashed()
                ->when($data->project_department, fn (Builder $q) => $q->where('id', $data->project_department))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->restore();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.project_departments.restore.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }

    public function destroy(Team $team, ProjectDepartmentOneOrManyRequest $data)
    {
        try {
            DB::beginTransaction();
            $count = $team->projectDepartments()
                ->withTrashed()
                ->when($data->project_department, fn (Builder $q) => $q->where('id', $data->project_department))
                ->when($data->ids, fn (Builder $q) => $q->whereIntegerInRaw('id', $data->ids))
                ->get()
                ->each->forceDelete();
            DB::commit();
            Services::toast()->success->execute(trans_choice('messages.project_departments.delete.success', $count));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();
            Services::toast()->error->execute();
        }

        return back();
    }
}
