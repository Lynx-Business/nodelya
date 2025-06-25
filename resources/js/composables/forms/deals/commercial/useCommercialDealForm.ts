import { useComputedForm } from '@/composables';

export function useCommercialDealForm(deal?: CommercialDealFormResource) {
    const form = useComputedForm({
        name: deal?.name || '',
        amount: deal?.amount || 0,
        code: deal?.code || '',
        reference: deal?.reference || '',
        success_rate: deal?.success_rate || 50,
        ordered_at: deal?.ordered_at || '',
        duration_in_months: deal?.duration_in_months || 12,
        starts_at: deal?.starts_at || '',
        schedule: deal?.schedule || [],
    });

    form.transform(transformDealForm);

    return form;
}

export type DealForm = ReturnType<typeof useCommercialDealForm>;
export type DealFormData = ReturnType<DealForm['data']>;

function transformDealForm(data: DealFormData): CommercialDealFormResource {
    const scheduleData: Record<string, any> = {};

    data.schedule.forEach((item: any) => {
        const year = item.date.split('-')[0];
        if (!scheduleData[year]) {
            scheduleData[year] = { data: [] };
        }

        scheduleData[year].data.push({
            date: item.date,
            amount: item.amount,
            status: item.status || 'pending',
            title: item.title || 'Échéance',
        });
    });

    return {
        ...data,
        schedule: scheduleData,
        amount_in_cents: data.amount * 100, // Conversion en cents
    };
}
