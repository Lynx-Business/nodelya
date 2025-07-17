import { useComputedForm } from '@/composables';
import type { FlowChargeData, FlowFormRequest } from '@/types/backend';

export function useFlowForm() {
    const form = useComputedForm<FlowFormRequest>({
        charges: [],
    });

    form.transform(transformFlowForm);

    return form;
}

export type FlowForm = ReturnType<typeof useFlowForm>;
export type FlowFormData = ReturnType<FlowForm['data']>;

function transformFlowForm(data: FlowFormRequest): FlowFormRequest {
    return {
        charges: Array.isArray(data.charges)
            ? data.charges.map((item: FlowChargeData) => ({
                  category_id: item.category_id,
                  category_name: item.category_name,
                  date: item.date,
                  amount: item.amount,
              }))
            : [],
    };
}
