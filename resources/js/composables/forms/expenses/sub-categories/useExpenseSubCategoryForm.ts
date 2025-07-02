import { useComputedForm } from '@/composables';
import { ExpenseSubCategoryFormRequest, ExpenseSubCategoryResource } from '@/types';
import { reactiveOmit } from '@vueuse/core';

export function useExpenseSubCategoryForm(expenseSubCategory?: Partial<ExpenseSubCategoryResource>) {
    const form = useComputedForm({
        expense_category: expenseSubCategory?.expense_category,
        name: expenseSubCategory?.name ?? '',
    });

    form.transform(transformExpenseSubCategoryForm);

    return form;
}

export type ExpenseSubCategoryForm = ReturnType<typeof useExpenseSubCategoryForm>;
export type ExpenseSubCategoryFormData = ReturnType<ExpenseSubCategoryForm['data']>;

export function transformExpenseSubCategoryForm(data: ExpenseSubCategoryFormData): ExpenseSubCategoryFormRequest {
    return {
        ...reactiveOmit(data, 'expense_category'),
        expense_category_id: data.expense_category?.id!,
    };
}
