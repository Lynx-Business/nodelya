import { useComputedForm } from '@/composables';
import { EmployeeResource, UpdateEmployeeEndsAtRequest } from '@/types';

export function useEmployeeEndsAtForm(employee?: Partial<EmployeeResource>) {
    const form = useComputedForm({
        ends_at: employee?.ends_at ?? '',
    });

    form.transform(transformEmployeeEndsAtForm);

    return form;
}

export type EmployeeEndsAtForm = ReturnType<typeof useEmployeeEndsAtForm>;
export type EmployeeEndsAtFormData = ReturnType<EmployeeEndsAtForm['data']>;

export function transformEmployeeEndsAtForm(data: EmployeeEndsAtFormData): UpdateEmployeeEndsAtRequest {
    return {
        ...data,
    };
}
