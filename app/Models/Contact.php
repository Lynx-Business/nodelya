<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use App\Traits\BelongsToTeam;
use App\Traits\HasFullName;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $team_id
 * @property int $client_id
 * @property bool $is_main
 * @property string $first_name
 * @property string $last_name
 * @property string $full_name
 * @property string|null $phone
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $client
 * @property-read \App\Models\Team $team
 *
 * @method static \Database\Factories\ContactFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact search(?string $q)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereBelongsToClient(\App\Models\Client|int $client)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereIsMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Contact extends Model
{
    use BelongsToClient;
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\ContactFactory> */
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
        'client_id',
        'is_main',
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
            'is_main' => 'boolean',
        ];
    }
}
