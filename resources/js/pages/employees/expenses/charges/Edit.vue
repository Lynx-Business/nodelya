<script setup lang="ts">
import ExpenseChargeForm from '@/components/expense/charge/ExpenseChargeForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import {
    Section,
    SectionContent,
    SectionDescription,
    SectionFooter,
    SectionHeader,
    SectionTitle,
} from '@/components/ui/custom/section';
import { useExpenseChargeForm, useLayout } from '@/composables';
import { AppLayout } from '@/layouts';
import { EmployeeExpenseChargeFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.employees.index.title'),
                href: route('employees.index'),
            },
            {
                title: trans('pages.employees.edit.title'),
                href: route('employees.edit', {
                    employee: route().params.employee,
                }),
            },
            {
                title: trans('pages.expenses.charges.index.title'),
                href: route('employees.expenses.charges.index', {
                    employee: route().params.employee,
                }),
            },
            {
                title: trans('pages.expenses.charges.edit.title'),
                href: route('employees.expenses.charges.edit', {
                    employee: route().params.employee,
                    expenseCharge: route().params.expenseCharge,
                }),
            },
        ],
    })),
});

const props = defineProps<EmployeeExpenseChargeFormProps>();

const form = useExpenseChargeForm(props.expenseCharge);

function submit() {
    const { employee, expenseCharge } = props;
    form.put(
        route('employees.expenses.charges.update', {
            employee,
            expenseCharge: expenseCharge!,
        }),
    );
}
</script>

<template>
    <Head :title="$t('pages.expenses.charges.edit.title')" />

    <Form :form :disabled="!expenseCharge?.can_update" @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.expenses.charges.edit.title') }}
                </SectionTitle>
                <SectionDescription>
                    {{ $t('pages.expenses.charges.edit.description') }}
                </SectionDescription>
            </SectionHeader>
            <SectionContent class="sm:flex">
                <ExpenseChargeForm autofocus />
            </SectionContent>
            <SectionFooter>
                <FormSubmitButton />
            </SectionFooter>
        </Section>
    </Form>
</template>
