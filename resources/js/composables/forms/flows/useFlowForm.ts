import { useComputedForm } from '@/composables';
import type { FlowChargeData, FlowFormRequest } from '@/types/backend';

export function useFlowForm() {
    const form = useComputedForm({
        charges: [] as FlowChargeData[],
    });

    form.transform(transformFlowForm);

    return form;
}

export type FlowForm = ReturnType<typeof useFlowForm>;
export type FlowFormData = ReturnType<FlowForm['data']>;

function transformFlowForm(data: FlowFormData): FlowFormRequest {
    return {
        charges: data.charges
            .filter((item) => item.category?.id != null)
            .map((item: FlowChargeData) => ({
                category_id: item.category!.id,
                date: item.date,
                amount: item.amount,
            })),
    };
}
