<?php

namespace App\Data\Flow\Form;

use App\Data\Flow\FlowCategoryResource;
use App\Data\Flow\FlowChargeResource;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class FlowFormProps extends Resource
{
    public function __construct(
        #[AutoInertiaLazy]
        #[DataCollectionOf(FlowCategoryResource::class)]
        public Lazy|DataCollection|Optional $flowCategories,

        #[AutoInertiaLazy]
        #[DataCollectionOf(FlowChargeResource::class)]
        public Lazy|DataCollection|Optional $flowCharges,
    ) {}
}
