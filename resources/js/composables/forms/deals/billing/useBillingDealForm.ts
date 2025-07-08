import { useComputedForm } from '@/composables';
import { BillingDealFormRequest, DealResource } from '@/types';

export function useBillingDealForm(deal?: DealResource) {
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
        reference: deal?.reference,
        project_department: deal?.project_department,
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

export type BillinDealForm = ReturnType<typeof useBillingDealForm>;
export type BillingDealFormData = ReturnType<BillinDealForm['data']>;

function transformDealForm(data: BillingDealFormData): BillingDealFormRequest {
    return {
        ...data,
        client_id: data.client?.id!,
        deal_id: data.parent?.id,
        deal: undefined,
        amount: data.amount!,
        project_department_id: data.project_department?.id!,
        schedule: Array.isArray(data.schedule) ? data.schedule : undefined,
    };
}
