<script setup lang="ts">
import { Checkbox } from '@/components/ui/checkbox';
import { DatePicker } from '@/components/ui/custom/date-picker';
import {
    FormContent,
    FormControl,
    FormError,
    FormField,
    FormLabel,
    FormProps,
    injectFormContext,
} from '@/components/ui/custom/form';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { AccountingPeriodFormData } from '@/composables';
import { AccountingPeriodResource } from '@/types';

type Props = FormProps & {
    accountingPeriod?: AccountingPeriodResource;
};
defineProps<Props>();

const { form } = injectFormContext<AccountingPeriodFormData>();
</script>

<template>
    <FormContent class="sm:grid-cols-2">
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.accounting_period.fields.starts_at') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <DatePicker v-model="form.starts_at" :autofocus />
            </FormControl>
            <FormError :message="form.errors.starts_at" />
        </FormField>
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.accounting_period.fields.ends_at') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <DatePicker v-model="form.ends_at" />
            </FormControl>
            <FormError :message="form.errors.ends_at" />
        </FormField>
        <template v-if="!accountingPeriod">
            <FormField class="col-span-full">
                <FormLabel>
                    <FormControl>
                        <Checkbox v-model="form.keep_general_expense_budgets" />
                    </FormControl>
                    <CapitalizeText>
                        {{ $t('components.accounting_period.form.fields.keep_general_expense_budgets') }}
                    </CapitalizeText>
                </FormLabel>
            </FormField>
            <FormField class="col-span-full">
                <FormLabel>
                    <FormControl>
                        <Checkbox v-model="form.keep_employee_expense_budgets" />
                    </FormControl>
                    <CapitalizeText>
                        {{ $t('components.accounting_period.form.fields.keep_employee_expense_budgets') }}
                    </CapitalizeText>
                </FormLabel>
            </FormField>
            <FormField class="col-span-full">
                <FormLabel>
                    <FormControl>
                        <Checkbox v-model="form.keep_contractor_expense_budgets" />
                    </FormControl>
                    <CapitalizeText>
                        {{ $t('components.accounting_period.form.fields.keep_contractor_expense_budgets') }}
                    </CapitalizeText>
                </FormLabel>
            </FormField>
        </template>
    </FormContent>
</template>
