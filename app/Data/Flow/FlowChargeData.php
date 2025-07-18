<?php

namespace App\Data\Flow;

use App\Facades\Services;
use App\Models\FlowCategory;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class FlowChargeData extends Data
{
    #[Hidden]
    #[Computed]
    public int $amount_in_cents;

    #[Computed]
    public ?FlowCategoryResource $category;

    public function __construct(
        public ?int $id,
        public int $category_id,
        public string $date,
        public float $amount,
    ) {
        $this->amount_in_cents = Services::conversion()->priceToCents($amount);

        if ($category_id) {
            $this->category = FlowCategoryResource::from(FlowCategory::find($category_id));
        }
    }
}
