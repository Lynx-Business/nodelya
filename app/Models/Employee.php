<?php

namespace App\Models;

use App\Traits\BelongsToProjectDepartment;
use App\Traits\BelongsToTeam;
use App\Traits\HasFullName;
use App\Traits\Searchable;
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
 * @property \Illuminate\Support\Carbon $starts_at
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProjectDepartment $projectDepartment
 * @property-read \App\Models\Team $team
 *
 * @method static \Database\Factories\EmployeeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee search(?string $q)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBelongsToProjectDepartment(\App\Models\ProjectDepartment|int $projectDepartment)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCreatedAt($value)
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
    use Searchable;

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
            'starts_at' => 'date',
            'ends_at'   => 'date',
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
