import { useComputedForm } from '@/composables';
import { ProjectDepartmentFormRequest, ProjectDepartmentResource } from '@/types';

export function useProjectDepartmentForm(projectDepartment?: ProjectDepartmentResource) {
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
