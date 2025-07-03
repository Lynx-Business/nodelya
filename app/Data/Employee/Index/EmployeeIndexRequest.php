<?php

namespace App\Data\Employee\Index;

use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\ProjectDepartment\ProjectDepartmentResource;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Models\AccountingPeriod;
use App\Models\ProjectDepartment;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class EmployeeIndexRequest extends Data
{
    #[Computed]
    public ?AccountingPeriodResource $accounting_period;

    #[Computed]
    #[DataCollectionOf(ProjectDepartmentResource::class)]
    public ?DataCollection $project_departments;

    public function __construct(
        public ?string $q = null,

        public ?int $page = null,

        public ?int $per_page = null,

        public string $sort_by = 'id',

        public string $sort_direction = 'desc',

        public ?TrashedFilter $trashed = null,

        public ?int $accounting_period_id = null,

        /** @var null|array<int> $project_department_ids */
        public ?array $project_department_ids = null,
    ) {
        /** @var ?AccountingPeriod $period */
        $period = null;
        if ($accounting_period_id) {
            $period = AccountingPeriod::find($accounting_period_id);
        } else {
            $period = Services::accountingPeriod()->current();
            $this->accounting_period_id = Services::accountingPeriod()->currentId();
        }
        if ($period) {
            $this->accounting_period = AccountingPeriodResource::from($period);
        }
        if ($project_department_ids) {
            $this->project_departments = ProjectDepartmentResource::collect(
                ProjectDepartment::query()
                    ->whereIntegerInRaw('id', $project_department_ids)
                    ->get(),
                DataCollection::class,
            );
        }
    }

    public static function attributes(): array
    {
        return [
            'q'                      => __('query'),
            'page'                   => __('page'),
            'per_page'               => __('per_page'),
            'sort_by'                => __('sort_by'),
            'sort_direction'         => __('sort_direction'),
            'trashed'                => __('trashed'),
            'accounting_period_id'   => __('models.accounting_period.name.one'),
            'project_department_ids' => __('models.project.department.name.many'),
        ];
    }

    public static function rules(ValidationContext $context): array
    {
        $accountingPeriod = app(AccountingPeriod::class);
        $projectDepartment = app(ProjectDepartment::class);
        $team = Services::team()->current();

        return [
            'accounting_period_id' => [
                Rule::exists($accountingPeriod->getTable(), $accountingPeriod->getKeyName())
                    ->where($accountingPeriod->getQualifiedTeamIdColumn(), $team?->getKey()),
            ],
            'project_department_ids.*' => [
                'integer',
                Rule::exists($projectDepartment->getTable(), $projectDepartment->getKeyName())
                    ->where($projectDepartment->getQualifiedTeamIdColumn(), $team?->getKey()),
            ],
        ];
    }
}
