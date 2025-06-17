import { useComputedForm } from '@/composables';
import { ClientFormRequest, ClientFormResource } from '@/types';

export function useClientForm(client?: ClientFormResource) {
    const form = useComputedForm({
        name: client?.name ?? '',
        address: {
            line1: client?.address?.address ?? '',
            line2: client?.address?.address_complement ?? '',
            city: client?.address?.city ?? '',
            state: client?.address?.state ?? '',
            postal_code: client?.address?.postal_code ?? '',
            country: client?.address?.country ?? '',
        },
    });

    form.transform(transformClientForm);

    return form;
}

export type ClientForm = ReturnType<typeof useClientForm>;
export type ClientFormData = ReturnType<ClientForm['data']>;

export function transformClientForm(data: ClientFormData): ClientFormRequest {
    return {
        ...data,
    };
}
