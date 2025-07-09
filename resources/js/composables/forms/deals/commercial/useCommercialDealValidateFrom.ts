import { useComputedForm } from '@/composables';
import { CommercialDealValidateRequest, DealResource } from '@/types';
import { reactiveOmit } from '@vueuse/core';

export function useCommercialDealValidateFrom(deal?: DealResource, reference?: string) {
    const form = useComputedForm({
        project_department: deal?.project_department,
        reference: reference || '',
    });

    form.transform(transformValidateDealForm);
    return form;
}

export type CommercialDealValidateForm = ReturnType<typeof useCommercialDealValidateFrom>;
export type CommercialDealValidateFormData = ReturnType<CommercialDealValidateForm['data']>;

function transformValidateDealForm(data: CommercialDealValidateFormData): CommercialDealValidateRequest {
    return {
        ...reactiveOmit(data, 'project_department'),
        project_department_id: data.project_department?.id!,
    };
}
