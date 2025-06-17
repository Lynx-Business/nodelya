import { useComputedForm } from '@/composables';
import { ProjectDepartmentFormRequest, ProjectDepartmentFormResource } from '@/types';

export function useProjectDepartmentForm(projectDepartment?: ProjectDepartmentFormResource) {
    const form = useComputedForm({
        name: projectDepartment?.name ?? '',
    });

    form.transform(transformProjectDepartmentForm);

    return form;
}

export type ProjectDepartmentForm = ReturnType<typeof useProjectDepartmentForm>;
export type ProjectDepartmentFormData = ReturnType<ProjectDepartmentForm['data']>;

export function transformProjectDepartmentForm(data: ProjectDepartmentFormData): ProjectDepartmentFormRequest {
    return {
        ...data,
    };
}
