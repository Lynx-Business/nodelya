import { useComputedForm } from '@/composables';
import { CommercialDealValidateRequest, DealResource } from '@/types';

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
        ...data,
        project_department_id: data.project_department?.id!,
    };
}
