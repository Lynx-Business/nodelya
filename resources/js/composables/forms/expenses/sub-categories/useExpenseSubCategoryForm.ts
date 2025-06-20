import { useComputedForm } from '@/composables';
import { ExpenseSubCategoryFormRequest, ExpenseSubCategoryFormResource } from '@/types';

export function useExpenseSubCategoryForm(expenseSubCategory?: Partial<ExpenseSubCategoryFormResource>) {
    const form = useComputedForm({
        name: expenseSubCategory?.name ?? '',
    });

    form.transform(transformExpenseSubCategoryForm);

    return form;
}

export type ExpenseSubCategoryForm = ReturnType<typeof useExpenseSubCategoryForm>;
export type ExpenseSubCategoryFormData = ReturnType<ExpenseSubCategoryForm['data']>;

export function transformExpenseSubCategoryForm(data: ExpenseSubCategoryFormData): ExpenseSubCategoryFormRequest {
    return {
        ...data,
    };
}
