<?php

namespace App\Models;

use App\Traits\BelongsToProjectDepartment;
use App\Traits\BelongsToTeam;
use App\Traits\HasFullName;
use App\Traits\HasPolicy;
use App\Traits\IsInAccountingPeriod;
use App\Traits\Searchable;
use App\Traits\Trashable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property int $team_id
 * @property int $project_department_id
 * @property string $first_name
 * @property string $last_name
 * @property string $full_name
 * @property string|null $phone
 * @property string|null $email
 * @property \Illuminate\Support\Carbon $starts_at
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read bool $can_delete
 * @property-read bool $can_restore
 * @property-read bool $can_trash
 * @property-read bool $can_update
 * @property-read bool $can_view
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpenseBudget> $expenseBudgets
 * @property-read int|null $expense_budgets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpenseCharge> $expenseCharges
 * @property-read int|null $expense_charges_count
 * @property-read true $is_trashable
 * @property bool $is_trashed
 * @property-read \App\Models\ProjectDepartment $projectDepartment
 * @property-read \App\Models\Team $team
 *
 * @method static \Database\Factories\EmployeeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee filterTrashed(\App\Enums\Trashed\TrashedFilter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee search(?string $q)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBelongsToProjectDepartment(\App\Models\ProjectDepartment|int $projectDepartment)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereProjectDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Employee extends Model
{
    use BelongsToProjectDepartment;
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory;

    use HasFullName;
    use HasPolicy;
    use IsInAccountingPeriod;
    use Searchable;
    use Trashable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'team_id',
        'project_department_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'starts_at',
        'ends_at',
    ];

    /**
     * The attributes that are searchable.
     *
     * @var list<string>
     */
    protected $searchable = [
        'projectDepartment.name',
        'full_name',
        'phone',
        'email',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at'   => 'datetime',
        ];
    }

    public function expenseBudgets(): MorphMany
    {
        return $this->morphMany(ExpenseBudget::class, 'model');
    }

    public function expenseCharges(): MorphMany
    {
        return $this->morphMany(ExpenseCharge::class, 'model');
    }

    public function isInAccountingPeriod(AccountingPeriod|int $accountingPeriod): bool
    {
        $accountingPeriod = is_int($accountingPeriod) ? AccountingPeriod::query()->findOrFail($accountingPeriod) : $accountingPeriod;

        return ($this->ends_at ?? $this->starts_at) <= $accountingPeriod->ends_at;
    }

    public function scopeWhereInAccountingPeriod(Builder $query, AccountingPeriod|int $accountingPeriod): Builder
    {
        $accountingPeriodModel = app(AccountingPeriod::class);
        $id = is_int($accountingPeriod) ? $accountingPeriod : $accountingPeriod->getKey();

        return $query->whereNot->whereExists(
            fn (QueryBuilder $q) => $q
                ->select(DB::raw(1))
                ->from($accountingPeriodModel->getTable())
                ->where($accountingPeriodModel->getQualifiedKeyName(), $id)
                ->where(
                    fn (QueryBuilder $q) => $q
                        ->whereColumn($accountingPeriodModel->qualifyColumn('starts_at'), '>=', $this->qualifyColumn('ends_at'))
                        ->orWhereColumn($accountingPeriodModel->qualifyColumn('ends_at'), '<=', $this->qualifyColumn('starts_at')),
                ),
        );
    }
}
