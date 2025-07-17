<?php

namespace App\Services;

use App\Actions\Flow\CreateOrUpdateFlowCategory;
use App\Actions\Flow\CreateOrUpdateFlowCharge;
use App\Data\Flow\FlowCategoryResource;
use App\Data\Flow\FlowChargeResource;
use App\Models\FlowCategory;
use App\Models\FlowCharge;
use Closure;

class FlowService
{
    public function __construct(
        public CreateOrUpdateFlowCategory $createOrUpdateCategory,
        public CreateOrUpdateFlowCharge $createOrUpdateCharge,
    ) {}

    /**
     * @param  ?(Closure($query): mixed)  $callback
     */
    public function categoriesList(?Closure $callback = null)
    {
        return FlowCategoryResource::collect(
            value($callback ?? FlowCategory::query(), FlowCategory::query())
                ->orderBy('name')
                ->get(),
        );
    }

    /**
     * @param  ?(Closure($query): mixed)  $callback
     */
    public function chargesList(?Closure $callback = null)
    {
        return FlowChargeResource::collect(
            value($callback ?? FlowCharge::query(), FlowCharge::query())
                ->orderByDesc('charged_at')
                ->get(),
        );
    }
}
