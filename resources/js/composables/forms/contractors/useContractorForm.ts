import { useComputedForm } from '@/composables';
import { ContractorFormRequest, ContractorResource } from '@/types';
import { reactiveOmit } from '@vueuse/core';

export function useContractorForm(contractor?: Partial<ContractorResource>) {
    const form = useComputedForm({
        project_department: contractor?.project_department,
        first_name: contractor?.first_name ?? '',
        last_name: contractor?.last_name ?? '',
        email: contractor?.email,
        phone: contractor?.phone,
    });

    form.transform(transformContractorForm);

    return form;
}

export type ContractorForm = ReturnType<typeof useContractorForm>;
export type ContractorFormData = ReturnType<ContractorForm['data']>;

export function transformContractorForm(data: ContractorFormData): ContractorFormRequest {
    return {
        ...reactiveOmit(data, 'project_department'),
        project_department_id: data.project_department?.id!,
    };
}
