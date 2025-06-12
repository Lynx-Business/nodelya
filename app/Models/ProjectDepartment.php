<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use App\Traits\Trashable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $team_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $is_trashable
 * @property bool $is_trashed
 * @property-read \App\Models\Team $team
 *
 * @method static \Database\Factories\ProjectDepartmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectDepartment filterTrashed(\App\Enums\Trashed\TrashedFilter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectDepartment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectDepartment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectDepartment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectDepartment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectDepartment whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectDepartment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectDepartment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectDepartment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectDepartment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectDepartment whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectDepartment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectDepartment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectDepartment withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ProjectDepartment extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\ProjectDepartmentFactory> */
    use HasFactory;

    use Trashable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'team_id',
        'name',
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
}
