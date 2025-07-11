import { useComputedForm } from '@/composables';
import { CommentRequestData, CommentResource } from '@/types';

export function useCommentForm(client?: CommentResource) {
    const form = useComputedForm({
        message: client?.message ?? '',
        model_type: client?.model_type,
        model_id: client?.model_id,
    });

    form.transform(transformCommentForm);

    return form;
}

export type CommentForm = ReturnType<typeof useCommentForm>;
export type CommentFormData = ReturnType<CommentForm['data']>;

export function transformCommentForm(data: CommentFormData): CommentRequestData {
    return {
        ...data,
        model_id: data?.model_id!,
        model_type: data?.model_type!,
    };
}
