import { useComputedForm } from '@/composables';
import { ExpenseBudgetFormRequest, ExpenseBudgetResource } from '@/types';
import { reactiveOmit } from '@vueuse/core';

export function useExpenseBudgetForm(expenseBudget?: Partial<ExpenseBudgetResource>) {
    const form = useComputedForm({
        expense_category: expenseBudget?.expense_item?.expense_category,
        expense_sub_category: expenseBudget?.expense_item?.expense_sub_category,
        expense_item: expenseBudget?.expense_item,
        starts_at: expenseBudget?.starts_at,
        ends_at: expenseBudget?.ends_at,
        amount: expenseBudget?.amount,
    });

    form.transform(transformExpenseBudgetForm);

    return form;
}

export type ExpenseBudgetForm = ReturnType<typeof useExpenseBudgetForm>;
export type ExpenseBudgetFormData = ReturnType<ExpenseBudgetForm['data']>;

export function transformExpenseBudgetForm(data: ExpenseBudgetFormData): ExpenseBudgetFormRequest {
    return {
        ...reactiveOmit(data, 'expense_category', 'expense_sub_category', 'expense_item'),
        expense_item_id: data.expense_item?.id!,
    };
}
