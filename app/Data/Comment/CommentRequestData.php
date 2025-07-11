<?php

namespace App\Data\Comment;

use App\Models\Comment;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class CommentRequestData extends Data
{
    public function __construct(

        #[Hidden]
        #[FromRouteParameter('comment')]
        public ?Comment $comment,

        public string $message,

        public string $model_type,

        public int $model_id,
    ) {}

    public static function attributes(): array
    {
        return [
            'message'    => __('models.comment.fields.message'),
            'model_type' => __('models.comment.fields.model_type'),
            'model_id'   => __('models.comment.fields.model_id'),
        ];
    }
}
