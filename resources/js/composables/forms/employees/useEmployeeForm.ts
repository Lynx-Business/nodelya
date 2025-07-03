import { useComputedForm } from '@/composables';
import { EmployeeFormRequest, EmployeeResource } from '@/types';
import { reactiveOmit } from '@vueuse/core';

export function useEmployeeForm(employee?: Partial<EmployeeResource>) {
    const form = useComputedForm({
        project_department: employee?.project_department,
        first_name: employee?.first_name ?? '',
        last_name: employee?.last_name ?? '',
        email: employee?.email ?? '',
        phone: employee?.phone,
        starts_at: employee?.starts_at ?? '',
    });

    form.transform(transformEmployeeForm);

    return form;
}

export type EmployeeForm = ReturnType<typeof useEmployeeForm>;
export type EmployeeFormData = ReturnType<EmployeeForm['data']>;

export function transformEmployeeForm(data: EmployeeFormData): EmployeeFormRequest {
    return {
        ...reactiveOmit(data, 'project_department'),
        project_department_id: data.project_department?.id!,
    };
}
