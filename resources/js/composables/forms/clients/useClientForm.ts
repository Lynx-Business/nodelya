import { useComputedForm } from '@/composables';
import { ClientFormRequest, ClientResource } from '@/types';

export function useClientForm(client?: ClientResource) {
    const form = useComputedForm({
        name: client?.name ?? '',
        address: {
            address: client?.address?.address ?? '',
            address_complement: client?.address?.address_complement ?? '',
            city: client?.address?.city ?? '',
            postal_code: client?.address?.postal_code ?? '',
            country: client?.address?.country ?? '',
        },
        comments: (client?.comments ?? []).map((comment) => ({
            id: comment.id,
            message: comment.message,
        })),
    });

    form.transform(transformClientForm);

    return form;
}

export type ClientForm = ReturnType<typeof useClientForm>;
export type ClientFormData = ReturnType<ClientForm['data']>;

export function transformClientForm(data: ClientFormData): ClientFormRequest {
    return {
        ...data,
        comments: data.comments.map((comment) => ({
            id: comment.id,
            message: comment.message,
        })),
    };
}
