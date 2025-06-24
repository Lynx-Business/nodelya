import { useComputedForm, useObjectOmit } from '@/composables';
import { ExpenseItemFormRequest, ExpenseItemFormResource } from '@/types';

export function useExpenseItemForm(expenseItem?: Partial<ExpenseItemFormResource>) {
    const form = useComputedForm({
        expense_category: expenseItem?.expense_category,
        expense_sub_category: expenseItem?.expense_sub_category,
        name: expenseItem?.name ?? '',
    });

    form.transform(transformExpenseItemForm);

    return form;
}

export type ExpenseItemForm = ReturnType<typeof useExpenseItemForm>;
export type ExpenseItemFormData = ReturnType<ExpenseItemForm['data']>;

export function transformExpenseItemForm(data: ExpenseItemFormData): ExpenseItemFormRequest {
    return {
        ...useObjectOmit(data, 'expense_category', 'expense_sub_category').value,
        expense_category_id: data.expense_category!.id,
        expense_sub_category_id: data.expense_sub_category?.id,
    };
}
