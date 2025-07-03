<script setup lang="ts">
import ExpenseBudgetForm from '@/components/expense/budget/ExpenseBudgetForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import {
    Section,
    SectionContent,
    SectionDescription,
    SectionFooter,
    SectionHeader,
    SectionTitle,
} from '@/components/ui/custom/section';
import { useExpenseBudgetForm, useLayout } from '@/composables';
import { AppLayout } from '@/layouts';
import { EmployeeExpenseBudgetFormProps } from '@/types';
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
                title: trans('pages.expenses.budgets.index.title'),
                href: route('employees.expenses.budgets.index', {
                    employee: route().params.employee,
                }),
            },
            {
                title: trans('pages.expenses.budgets.edit.title'),
                href: route('employees.expenses.budgets.create', {
                    employee: route().params.employee,
                }),
            },
        ],
    })),
});

const props = defineProps<EmployeeExpenseBudgetFormProps>();

const form = useExpenseBudgetForm({
    model_type: 'employee',
    model_id: props.employee.id,
});

function submit() {
    const { employee } = props;
    form.post(
        route('employees.expenses.budgets.create', {
            employee,
        }),
    );
}
</script>

<template>
    <Head :title="$t('pages.expenses.budgets.create.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.expenses.budgets.create.title') }}
                </SectionTitle>
                <SectionDescription>
                    {{ $t('pages.expenses.budgets.create.description') }}
                </SectionDescription>
            </SectionHeader>
            <SectionContent class="sm:flex">
                <ExpenseBudgetForm autofocus />
            </SectionContent>
            <SectionFooter>
                <FormSubmitButton />
            </SectionFooter>
        </Section>
    </Form>
</template>
