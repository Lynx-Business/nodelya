import { useComputedForm } from '@/composables';
import { DealListResource } from '@/types';

export function useCommercialDealValidateFrom(deal?: DealListResource, reference?: string) {
    const form = useComputedForm({
        amount: deal?.amount || 0,
        reference: reference || '',
    });

    return form;
}

export type CommercialDealValidateForm = ReturnType<typeof useCommercialDealValidateFrom>;
export type CommercialDealValidateFormData = ReturnType<CommercialDealValidateForm['data']>;
