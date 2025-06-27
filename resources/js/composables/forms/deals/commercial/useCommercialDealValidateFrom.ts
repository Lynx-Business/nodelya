import { useComputedForm } from '@/composables';
import { CommercialDealFormResource } from '@/types';

export function useCommercialDealValidateFrom(deal?: CommercialDealFormResource, reference?: string) {
    const form = useComputedForm({
        amount: deal?.amount_in_cents || 0,
        reference: reference || '',
    });

    return form;
}

export type CommercialDealValidateForm = ReturnType<typeof useCommercialDealValidateFrom>;
export type CommercialDealValidateFormData = ReturnType<CommercialDealValidateForm['data']>;
