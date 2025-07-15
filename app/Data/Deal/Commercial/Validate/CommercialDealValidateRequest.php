<?php

namespace App\Data\Deal\Commercial\Validate;

use App\Data\Expense\Charge\ContractorExpenseChargeData;
use App\Data\ProjectDepartment\ProjectDepartmentResource;
use App\Facades\Services;
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
class CommercialDealValidateRequest extends Data
{
    #[Computed]
    public ?ProjectDepartmentResource $project_department;

    public function __construct(

        public ?int $project_department_id,

        public string $reference,

        #[DataCollectionOf(ContractorExpenseChargeData::class)]
        public ?DataCollection $expense_charges,
    ) {
        if ($project_department_id) {
            $this->project_department = ProjectDepartmentResource::from(
                ProjectDepartment::find($project_department_id),
            );
        }
    }

    public static function attributes(): array
    {
        return [
            'amount'    => __('models.deal.commercial.fields.amount'),
            'reference' => __('models.deal.commercial.fields.reference'),
        ];
    }

    public static function rules(ValidationContext $context): array
    {
        $projectDepartment = app(ProjectDepartment::class);
        $team = Services::team()->current();

        return [
            'project_department_id' => [
                'nullable',
                'integer',
                Rule::exists($projectDepartment->getTable(), $projectDepartment->getKeyName())
                    ->where($projectDepartment->getQualifiedTeamIdColumn(), $team?->getKey()),
            ],
        ];
    }
}
