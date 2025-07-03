import { useComputedForm } from '@/composables';
import { ExpenseChargeFormRequest, ExpenseChargeResource } from '@/types';
import { reactiveOmit } from '@vueuse/core';

export function useExpenseChargeForm(expenseCharge?: Partial<ExpenseChargeResource>) {
    const form = useComputedForm({
        expense_category: expenseCharge?.expense_item?.expense_category,
        expense_sub_category: expenseCharge?.expense_item?.expense_sub_category,
        expense_item: expenseCharge?.expense_item,
        charged_at: expenseCharge?.charged_at ?? '',
        amount: expenseCharge?.amount ?? 0,
    });

    form.transform(transformExpenseChargeForm);

    return form;
}

export type ExpenseChargeForm = ReturnType<typeof useExpenseChargeForm>;
export type ExpenseChargeFormData = ReturnType<ExpenseChargeForm['data']>;

export function transformExpenseChargeForm(data: ExpenseChargeFormData): ExpenseChargeFormRequest {
    return {
        ...reactiveOmit(data, 'expense_category', 'expense_sub_category', 'expense_item'),
        expense_item_id: data.expense_item?.id!,
    };
}
