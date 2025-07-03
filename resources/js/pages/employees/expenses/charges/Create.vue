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
                href: route('employees.expenses.charges.create', {
                    employee: route().params.employee,
                }),
            },
        ],
    })),
});

const props = defineProps<EmployeeExpenseChargeFormProps>();

const form = useExpenseChargeForm({
    model_type: 'employee',
    model_id: props.employee.id,
});

function submit() {
    const { employee } = props;
    form.post(
        route('employees.expenses.charges.create', {
            employee,
        }),
    );
}
</script>

<template>
    <Head :title="$t('pages.expenses.charges.create.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.expenses.charges.create.title') }}
                </SectionTitle>
                <SectionDescription>
                    {{ $t('pages.expenses.charges.create.description') }}
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
