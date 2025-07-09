<?php

namespace App\Data\User;

use App\Models\Contractor;
use App\Models\Deal;
use App\Models\Employee;
use App\Models\ExpenseBudget;
use App\Models\ExpenseCharge;
use App\Models\Team;
use App\Models\User;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

#[TypeScript]
class UserAbilitiesResource extends Resource
{
    public function __construct(
        #[TypeScriptType([
            'view_any' => 'bool',
            'create'   => 'bool',
        ])]
        public array $contractors,

        #[TypeScriptType([
            'view_any' => 'bool',
            'create'   => 'bool',
        ])]
        public array $employees,

        #[TypeScriptType([
            'budgets' => [
                'view_any' => 'bool',
                'create'   => 'bool',
            ],
            'charges' => [
                'view_any' => 'bool',
                'create'   => 'bool',
            ],
        ])]
        public array $expenses,

        #[TypeScriptType([
            'view_any' => 'bool',
            'create'   => 'bool',
        ])]
        public array $deals,

        #[TypeScriptType([
            'view_any' => 'bool',
            'create'   => 'bool',
        ])]
        public array $teams,

        #[TypeScriptType([
            'view_any' => 'bool',
            'create'   => 'bool',
        ])]
        public array $users,
    ) {}

    public static function fromModel(User $user): self
    {
        return self::from([
            'contractors' => [
                'view_any' => $user->can('viewAny', Contractor::class),
                'create'   => $user->can('create', Contractor::class),
            ],
            'employees' => [
                'view_any' => $user->can('viewAny', Employee::class),
                'create'   => $user->can('create', Employee::class),
            ],
            'expenses' => [
                'budgets' => [
                    'view_any' => $user->can('viewAny', ExpenseBudget::class),
                    'create'   => $user->can('create', ExpenseBudget::class),
                ],
                'charges' => [
                    'view_any' => $user->can('viewAny', ExpenseCharge::class),
                    'create'   => $user->can('create', ExpenseCharge::class),
                ],
            ],
            'teams' => [
                'view_any' => $user->can('viewAny', Team::class),
                'create'   => $user->can('create', Team::class),
            ],
            'users' => [
                'view_any' => $user->can('viewAny', User::class),
                'create'   => $user->can('create', User::class),
            ],
            'deals' => [
                'view_any' => $user->can('viewAny', Deal::class),
                'create'   => $user->can('create', Deal::class),
            ],
        ]);
    }
}
