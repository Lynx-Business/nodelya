<?php

namespace App\Data\Client\Form;

use App\Data\Client\ClientResource;
use App\Data\Comment\CommentResource;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ClientFormProps extends Resource
{
    public function __construct(
        public ?ClientResource $client,

        #[DataCollectionOf(CommentResource::class)]
        public ?DataCollection $comments,
    ) {}
}
