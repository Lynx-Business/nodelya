import { useComputedForm, useObjectOmit } from '@/composables';
import { ExpenseSubCategoryFormRequest, ExpenseSubCategoryFormResource } from '@/types';

export function useExpenseSubCategoryForm(expenseSubCategory?: Partial<ExpenseSubCategoryFormResource>) {
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
        ...useObjectOmit(data, 'expense_category').value,
        expense_category_id: data.expense_category!.id,
    };
}
