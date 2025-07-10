<?php

namespace App\Data\Deal;

use App\Data\Client\ClientResource;
use App\Data\ProjectDepartment\ProjectDepartmentResource;
use App\Models\Deal;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\RecordTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DealResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        public float $amount,
        public ?string $code,
        public ?string $reference,
        public int $success_rate,
        public string $ordered_at,
        public ?int $duration_in_months,
        public ?string $starts_at,
        public Lazy|ClientResource $client,
        public Lazy|ProjectDepartmentResource $project_department,
        public Lazy|DealResource $parent,
        #[DataCollectionOf(DealScheduleData::class)]
        public DataCollection|Lazy $schedule,
        #[RecordTypeScriptType('string', MonthlyExpenseData::class)]
        public Lazy|array $monthly_expenses,
        public Lazy|bool $can_view,
        public Lazy|bool $can_update,
        public Lazy|bool $can_trash,
        public Lazy|bool $can_restore,
        public Lazy|bool $can_delete,
    ) {}

    public static function fromModel(Deal $deal): static
    {
        return new static(
            id: $deal->id,
            name: $deal->name,
            amount: $deal->amount,
            code: $deal->code,
            reference: $deal->reference ?? null,
            success_rate: $deal->success_rate,
            ordered_at: $deal->ordered_at,
            duration_in_months: $deal->duration_in_months ?? null,
            starts_at: $deal->starts_at ?? null,
            client: Lazy::whenLoaded('client', $deal, fn () => ClientResource::from($deal->client)),
            project_department: Lazy::whenLoaded('projectDepartment', $deal, fn () => ProjectDepartmentResource::from($deal->projectDepartment)),
            parent: Lazy::whenLoaded('parent', $deal, fn () => DealResource::from($deal->parent)),
            monthly_expenses: Lazy::create(fn () => $deal->monthlyExpenses),
            schedule: Lazy::create(fn () => $deal->schedule),
            can_view: Lazy::create(fn () => $deal->can_view),
            can_update: Lazy::create(fn () => $deal->can_update),
            can_trash: Lazy::create(fn () => $deal->can_trash),
            can_restore: Lazy::create(fn () => $deal->can_restore),
            can_delete: Lazy::create(fn () => $deal->can_delete),
        );
    }
}
