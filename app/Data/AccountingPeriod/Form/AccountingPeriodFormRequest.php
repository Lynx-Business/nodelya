<?php

namespace App\Data\AccountingPeriod\Form;

use App\Models\AccountingPeriod;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelData\Attributes\FromAuthenticatedUserProperty;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\Before;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\References\FieldReference;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\Hidden;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class AccountingPeriodFormRequest extends Data
{
    public function __construct(
        #[Hidden]
        #[FromAuthenticatedUserProperty]
        #[FromRouteParameter('team')]
        public Team $team,

        #[Hidden]
        #[FromRouteParameter('accountingPeriod')]
        public ?AccountingPeriod $accounting_period,

        #[Before(new FieldReference('ends_at'))]
        public Carbon $starts_at,

        #[After(new FieldReference('starts_at'))]
        public Carbon $ends_at,
    ) {}

    public static function attributes(): array
    {
        return [
            'starts_at' => __('models.accounting_period.fields.starts_at'),
            'ends_at'   => __('models.accounting_period.fields.ends_at'),
        ];
    }

    public static function rules(
        ValidationContext $context,

        #[CurrentUser]
        User $user,

        #[RouteParameter('team')]
        ?Team $team,

        #[RouteParameter('accountingPeriod')]
        ?AccountingPeriod $accountingPeriod,
    ): array {
        $startsAt = data_get($context->payload, 'starts_at');
        $endsAt = data_get($context->payload, 'ends_at');

        $team ??= $user->team;

        return [
            'starts_at' => [
                fn (string $attribute, mixed $value, Closure $fail) => AccountingPeriod::query()
                    ->whereBelongsToTeam($team)
                    ->when($accountingPeriod?->exists, fn (Builder $q) => $q->whereNot($accountingPeriod->getQualifiedKeyName(), $accountingPeriod->getKey()))
                    ->where(fn (Builder $q) => $q
                        ->whereBetween('starts_at', [$startsAt, $endsAt])
                        ->orWhereBetween('ends_at', [$startsAt, $endsAt])
                        ->orWhere(fn (Builder $q) => $q
                            ->where('starts_at', '<=', $startsAt)
                            ->where('ends_at', '>=', $startsAt),
                        )
                        ->orWhere(fn (Builder $q) => $q
                            ->where('starts_at', '<=', $endsAt)
                            ->where('ends_at', '>=', $endsAt),
                        ),

                    )
                    ->exists()
                    && $fail('validation.custom.accounting_period.overlap')->translate(),
            ],
        ];
    }
}
