<?php

namespace App\Attributes;

use Attribute;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Array_;
use Spatie\LaravelData\Attributes\Validation\CustomValidationAttribute;
use Spatie\LaravelData\Support\Validation\ValidationPath;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptTransformableAttribute;
use Spatie\TypeScriptTransformer\Types\StructType;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class EnumArrayOf extends CustomValidationAttribute implements TypeScriptTransformableAttribute
{
    public function __construct(
        protected string $enum,
    ) {}

    public function getType(): Type
    {
        return new Array_(StructType::fromArray([
            'value' => $this->enum,
            'label' => 'string',
        ]));
    }

    /**
     * @return array<object|string>|object|string
     */
    public function getRules(ValidationPath $path): array|object|string
    {
        return [Rule::forEach(fn () => [Rule::enum($this->enum)])];
    }
}
