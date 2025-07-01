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
                title: trans('pages.expenses.title'),
                href: route('expenses.budgets.index'),
            },
            {
                title: trans('pages.expenses.budgets.index.title'),
                href: route('expenses.budgets.index'),
            },
            {
                title: trans('pages.expenses.budgets.edit.title'),
                href: route('expenses.budgets.edit', {
                    expenseBudget: route().params.expenseBudget,
                }),
            },
        ],
    })),
});

const props = defineProps<ExpenseBudgetFormProps>();

const form = useExpenseBudgetForm(props.expenseBudget);

function submit() {
    const { expenseBudget } = props;
    form.put(route('expenses.budgets.update', { expenseBudget: expenseBudget! }));
}
</script>

<template>
    <Head :title="$t('pages.expenses.budgets.edit.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.expenses.budgets.edit.title') }}
                </SectionTitle>
                <SectionDescription>
                    {{ $t('pages.expenses.budgets.edit.description') }}
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
