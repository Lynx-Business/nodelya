import { useComputedForm } from '@/composables';
import { CommercialDealFormRequest, DealResource } from '@/types';
import { reactiveOmit } from '@vueuse/core';

export function useCommercialDealForm(deal?: DealResource) {
    const initialSchedule =
        deal?.schedule?.flatMap((yearData) =>
            yearData.data.map((item) => ({
                date: item.date,
                amount: item.amount,
                status: item.status,
                title: item.title,
            })),
        ) || [];

    const form = useComputedForm({
        name: deal?.name || '',
        amount: deal?.amount,
        parent: deal?.parent,
        client: deal?.client,
        code: deal?.code || '',
        success_rate: deal?.success_rate || 50,
        ordered_at: deal?.ordered_at || '',
        duration_in_months: deal?.duration_in_months || 12,
        starts_at: deal?.starts_at || '',
        schedule: deal?.schedule,
        schedule_data: initialSchedule,
    });

    form.transform(transformDealForm);

    return form;
}

export type CommercialDealForm = ReturnType<typeof useCommercialDealForm>;
export type CommercialDealFormData = ReturnType<CommercialDealForm['data']>;

function transformDealForm(data: CommercialDealFormData): CommercialDealFormRequest {
    return {
        ...reactiveOmit(data, 'client', 'parent'),
        client_id: data.client?.id!,
        deal_id: data.parent?.id,
        amount: data.amount!,
        schedule: Array.isArray(data.schedule) ? data.schedule : undefined,
    };
}
