import { useComputedForm, useFormatter, useParser } from '@/composables';
import { AccountingPeriodFormRequest, AccountingPeriodResource } from '@/types';

export function useAccountingPeriodForm(accountingPeriod?: Partial<AccountingPeriodResource>) {
    const parse = useParser();
    const format = useFormatter();

    let startsAt = accountingPeriod?.starts_at ?? '';

    const form = useComputedForm({
        get starts_at(): string {
            return startsAt;
        },
        set starts_at(value: string) {
            startsAt = value;
            this.ends_at = format.timestamp(parse.toDate(value)?.add({ years: 1, days: -1 }));
        },
        ends_at: accountingPeriod?.ends_at ?? '',
        keep_general_expense_budgets: true,
        keep_employee_expense_budgets: true,
        keep_contractor_expense_budgets: true,
    });
    if (startsAt && !form.ends_at) {
        form.starts_at = startsAt;
    }

    form.transform(transformAccountingPeriodForm);

    return form;
}

export type AccountingPeriodForm = ReturnType<typeof useAccountingPeriodForm>;
export type AccountingPeriodFormData = ReturnType<AccountingPeriodForm['data']>;

export function transformAccountingPeriodForm(data: AccountingPeriodFormData): AccountingPeriodFormRequest {
    return {
        ...data,
    };
}
