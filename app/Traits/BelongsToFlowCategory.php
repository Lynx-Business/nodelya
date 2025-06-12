<?php

namespace App\Traits;

use App\Models\FlowCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToFlowCategory
{
    public static function bootBelongsToFlowCategory(): void {}

    public function initializeBelongsToFlowCategory(): void {}

    public function getFlowCategoryIdColumn(): string
    {
        return defined(static::class.'::FLOW_CATEGORY_ID') ? static::FLOW_CATEGORY_ID : 'flow_category_id';
    }

    public function getQualifiedFlowCategoryIdColumn(): string
    {
        return $this->qualifyColumn($this->getFlowCategoryIdColumn());
    }

    public function flowCategory(): BelongsTo
    {
        return $this->belongsTo(FlowCategory::class, $this->getFlowCategoryIdColumn());
    }

    public function scopeWhereBelongsToFlowCategory(Builder $query, FlowCategory|int $flowCategory): Builder
    {
        $flowCategory = is_int($flowCategory) ? $flowCategory : $flowCategory->getKey();

        return $query
            ->where($this->getQualifiedFlowCategoryIdColumn(), $flowCategory);
    }
}
