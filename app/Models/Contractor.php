<?php

namespace App\Models;

use App\Traits\BelongsToProjectDepartment;
use App\Traits\BelongsToTeam;
use App\Traits\HasFullName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property int $team_id
 * @property int $project_department_id
 * @property string $first_name
 * @property string $last_name
 * @property string $full_name
 * @property string|null $phone
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProjectDepartment $projectDepartment
 * @property-read \App\Models\Team $team
 *
 * @method static \Database\Factories\ContractorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contractor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contractor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contractor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contractor whereBelongsToProjectDepartment(\App\Models\ProjectDepartment|int $projectDepartment)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contractor whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contractor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contractor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contractor whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contractor whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contractor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contractor whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contractor wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contractor whereProjectDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contractor whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contractor whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Contractor extends Model
{
    use BelongsToProjectDepartment;
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\ContractorFactory> */
    use HasFactory;

    use HasFullName;

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
    ];

    /**
     * The attributes that are searchable.
     *
     * @var list<string>
     */
    protected $searchable = [
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
            //
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
}
