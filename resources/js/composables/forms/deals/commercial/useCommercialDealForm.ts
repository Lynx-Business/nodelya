import { useComputedForm } from '@/composables';
import { CommercialDealFormRequest, CommercialDealFormResource } from '@/types';

export function useCommercialDealForm(deal?: CommercialDealFormResource) {
    const initialSchedule =
        deal?.schedule?.years?.flatMap((yearData) =>
            yearData.data.map((item) => ({
                date: item.date,
                amount: item.amount,
                status: item.status,
                title: item.title,
            })),
        ) || [];

    const form = useComputedForm({
        name: deal?.name || '',
        amount_in_cents: deal?.amount_in_cents || 0,
        client_id: deal?.client_id,
        code: deal?.code || '',
        reference: deal?.reference || '',
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

export type DealForm = ReturnType<typeof useCommercialDealForm>;
export type DealFormData = ReturnType<DealForm['data']>;

function transformDealForm(data: DealFormData): CommercialDealFormRequest {
    return {
        ...data,
    };
}
