import { useComputedForm } from '@/composables';
import { ExpenseCategoryFormRequest, ExpenseCategoryResource } from '@/types';

export function useExpenseCategoryForm(expenseCategory?: Partial<ExpenseCategoryResource>) {
    const form = useComputedForm({
        type: expenseCategory?.type ?? 'general',
        name: expenseCategory?.name ?? '',
    });

    form.transform(transformExpenseCategoryForm);

    return form;
}

export type ExpenseCategoryForm = ReturnType<typeof useExpenseCategoryForm>;
export type ExpenseCategoryFormData = ReturnType<ExpenseCategoryForm['data']>;

export function transformExpenseCategoryForm(data: ExpenseCategoryFormData): ExpenseCategoryFormRequest {
    return {
        ...data,
    };
}
