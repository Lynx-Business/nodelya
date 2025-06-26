<?php

namespace App\Models;

use App\Data\Team\TeamSettingsData;
use App\Traits\BelongsToCreator;
use App\Traits\HasPolicy;
use App\Traits\InteractsWithMedia;
use App\Traits\Searchable;
use App\Traits\Trashable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;

/**
 * @property int $id
 * @property int|null $creator_id
 * @property string $name
 * @property \Spatie\LaravelData\Contracts\BaseData|null $settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AccountingPeriod> $accountingPeriods
 * @property-read int|null $accounting_periods_count
 * @property-read bool $can_delete
 * @property-read bool $can_restore
 * @property-read bool $can_trash
 * @property-read bool $can_update
 * @property-read bool $can_view
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpenseCategory> $expenseCategories
 * @property-read int|null $expense_categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpenseItem> $expenseItems
 * @property-read int|null $expense_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpenseSubCategory> $expenseSubCategories
 * @property-read int|null $expense_sub_categories_count
 * @property-read true $is_trashable
 * @property bool $is_trashed
 * @property-read \App\Models\Media|null $logo
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectDepartment> $projectDepartments
 * @property-read int|null $project_departments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\TeamFactory factory($count = null, $state = [])
 * @method static Builder<static>|Team filterTrashed(\App\Enums\Trashed\TrashedFilter $filter)
 * @method static Builder<static>|Team newModelQuery()
 * @method static Builder<static>|Team newQuery()
 * @method static Builder<static>|Team onlyTrashed()
 * @method static Builder<static>|Team query()
 * @method static Builder<static>|Team search(?string $q)
 * @method static Builder<static>|Team whereBelongsToCreator(\App\Models\User|int $creator)
 * @method static Builder<static>|Team whereCreatedAt($value)
 * @method static Builder<static>|Team whereCreatorId($value)
 * @method static Builder<static>|Team whereDeletedAt($value)
 * @method static Builder<static>|Team whereId($value)
 * @method static Builder<static>|Team whereName($value)
 * @method static Builder<static>|Team whereSettings($value)
 * @method static Builder<static>|Team whereUpdatedAt($value)
 * @method static Builder<static>|Team withTrashed()
 * @method static Builder<static>|Team withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Team extends Model implements HasMedia
{
    use BelongsToCreator;

    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    use HasPolicy;
    use InteractsWithMedia;
    use Searchable;
    use Trashable;

    const string COLLECTION_LOGO = 'logo';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'creator_id',
        'name',
        'settings',
    ];

    /**
     * The attributes that are searchable.
     *
     * @var list<string>
     */
    protected $searchable = [
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
            'settings' => TeamSettingsData::class,
        ];
    }

    public static function booted(): void {}

    public function accountingPeriods(): HasMany
    {
        return $this->hasMany(AccountingPeriod::class)->whereBelongsToAnyTeam();
    }

    public function expenseCategories(): HasMany
    {
        return $this->hasMany(ExpenseCategory::class)->whereBelongsToAnyTeam();
    }

    public function expenseItems(): HasMany
    {
        return $this->hasMany(ExpenseItem::class)->whereBelongsToAnyTeam();
    }

    public function expenseSubCategories(): HasMany
    {
        return $this->hasMany(ExpenseSubCategory::class)->whereBelongsToAnyTeam();
    }

    public function projectDepartments(): HasMany
    {
        return $this->hasMany(ProjectDepartment::class)->whereBelongsToAnyTeam();
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'model', 'model_has_roles')->distinct();
    }

    public function logo(): MorphOne
    {
        return $this->media()->one()
            ->latestOfMany()
            ->withAttributes('collection_name', static::COLLECTION_LOGO);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::COLLECTION_LOGO)
            ->acceptsMimeTypes(['image/svg+xml'])
            ->singleFile();
    }
}
