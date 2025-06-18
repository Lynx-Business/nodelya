import { useComputedForm, useFormatter, useParser } from '@/composables';
import { AccountingPeriodFormRequest, AccountingPeriodFormResource } from '@/types';

export function useAccountingPeriodForm(accountingPeriod?: AccountingPeriodFormResource) {
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
    });

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
