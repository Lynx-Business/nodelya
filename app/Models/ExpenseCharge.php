<?php

namespace App\Models;

use App\Enums\Expense\ExpenseType;
use App\Facades\Services;
use App\Traits\BelongsToAccountingPeriod;
use App\Traits\BelongsToExpenseItem;
use App\Traits\BelongsToTeam;
use App\Traits\HasPolicy;
use App\Traits\Searchable;
use App\Traits\Trashable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @property int $id
 * @property int $team_id
 * @property int $expense_item_id
 * @property int $accounting_period_id
 * @property string|null $model_type
 * @property int|null $model_id
 * @property int $amount_in_cents
 * @property \Illuminate\Support\Carbon $charged_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $deal_id
 * @property-read \App\Models\AccountingPeriod $accountingPeriod
 * @property float $amount
 * @property-read bool $can_delete
 * @property-read bool $can_restore
 * @property-read bool $can_trash
 * @property-read bool $can_update
 * @property-read bool $can_view
 * @property-read \App\Models\Deal|null $deal
 * @property-read \App\Models\ExpenseItem $expenseItem
 * @property-read true $is_trashable
 * @property bool $is_trashed
 * @property-read Model|\Eloquent|null $model
 * @property-read \App\Models\Team $team
 * @property-read ExpenseType $type
 *
 * @method static \Database\Factories\ExpenseChargeFactory factory($count = null, $state = [])
 * @method static Builder<static>|ExpenseCharge filterTrashed(\App\Enums\Trashed\TrashedFilter $filter)
 * @method static Builder<static>|ExpenseCharge newModelQuery()
 * @method static Builder<static>|ExpenseCharge newQuery()
 * @method static Builder<static>|ExpenseCharge onlyTrashed()
 * @method static Builder<static>|ExpenseCharge query()
 * @method static Builder<static>|ExpenseCharge search(?string $q)
 * @method static Builder<static>|ExpenseCharge whereAccountingPeriodId($value)
 * @method static Builder<static>|ExpenseCharge whereAmountInCents($value)
 * @method static Builder<static>|ExpenseCharge whereBelongsToAccountingPeriod(\App\Models\AccountingPeriod|int $accountingPeriod)
 * @method static Builder<static>|ExpenseCharge whereBelongsToExpenseItem(\App\Models\ExpenseItem|int $expenseItem)
 * @method static Builder<static>|ExpenseCharge whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static Builder<static>|ExpenseCharge whereChargedAt($value)
 * @method static Builder<static>|ExpenseCharge whereCreatedAt($value)
 * @method static Builder<static>|ExpenseCharge whereDealId($value)
 * @method static Builder<static>|ExpenseCharge whereDeletedAt($value)
 * @method static Builder<static>|ExpenseCharge whereExpenseItemId($value)
 * @method static Builder<static>|ExpenseCharge whereId($value)
 * @method static Builder<static>|ExpenseCharge whereModelId($value)
 * @method static Builder<static>|ExpenseCharge whereModelType($value)
 * @method static Builder<static>|ExpenseCharge whereTeamId($value)
 * @method static Builder<static>|ExpenseCharge whereType(\App\Enums\Expense\ExpenseType $type)
 * @method static Builder<static>|ExpenseCharge whereUpdatedAt($value)
 * @method static Builder<static>|ExpenseCharge withTrashed()
 * @method static Builder<static>|ExpenseCharge withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ExpenseCharge extends Model
{
    use BelongsToAccountingPeriod;
    use BelongsToExpenseItem;
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\ExpenseChargeFactory> */
    use HasFactory;

    use HasPolicy;
    use Searchable;
    use Trashable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'team_id',
        'accounting_period_id',
        'expense_item_id',
        'deal_id',
        'model_type',
        'model_id',
        'amount_in_cents',
        'charged_at',
    ];

    /**
     * The attributes that are searchable.
     *
     * @var list<string>
     */
    protected $searchable = [
        'expenseItem.expenseCategory.name',
        'expenseItem.expenseSubCategory.name',
        'expenseItem.name',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'charged_at' => 'datetime',
        ];
    }

    public function model(): MorphTo
    {
        return $this->morphTo('model');
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes): float => Services::conversion()->centsToPrice(data_get($attributes, 'amount_in_cents')),
            set: fn (?float $value) => ['amount_in_cents' => Services::conversion()->priceToCents($value)],
        );
    }

    protected function type(): Attribute
    {
        return Attribute::get(fn (): ExpenseType => ExpenseType::fromMorphType($this->model_type));
    }

    public function scopeWhereType(Builder $query, ExpenseType $type): Builder
    {
        return $query->where(
            'model_type',
            match ($type) {
                ExpenseType::CONTRACTOR => Relation::getMorphAlias(Contractor::class),
                ExpenseType::EMPLOYEE   => Relation::getMorphAlias(Employee::class),
                ExpenseType::GENERAL    => null,
                default                 => null,
            },
        );
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }
}
