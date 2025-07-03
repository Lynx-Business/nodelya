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
import { ExpenseBudgetFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('enums.expense.type.general'),
                href: route('expenses.budgets.index'),
            },
            {
                title: trans('pages.expenses.budgets.create.title'),
                href: route('expenses.budgets.create'),
            },
        ],
    })),
});

defineProps<ExpenseBudgetFormProps>();

const form = useExpenseBudgetForm();

function submit() {
    form.post(route('expenses.budgets.create'));
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
