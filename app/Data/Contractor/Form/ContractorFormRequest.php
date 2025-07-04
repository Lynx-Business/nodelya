<?php

namespace App\Data\Contractor\Form;

use App\Models\Contractor;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\Hidden;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class ContractorFormRequest extends Data
{
    public function __construct(
        #[Hidden]
        #[FromRouteParameter('contractor')]
        public ?Contractor $contractor,

        public int $project_department_id,

        public string $first_name,

        public string $last_name,

        public ?string $email,

        public ?string $phone,
    ) {}

    public static function attributes(): array
    {
        return [
            'project_department_id' => __('models.project_department_id.name.one'),
            'first_name'            => __('models.contractor.fields.first_name'),
            'last_name'             => __('models.contractor.fields.last_name'),
            'email'                 => __('models.contractor.fields.email'),
            'phone'                 => __('models.contractor.fields.phone'),
        ];
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            //
        ];
    }
}
