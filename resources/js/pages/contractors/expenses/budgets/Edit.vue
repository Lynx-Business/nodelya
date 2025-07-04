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
import { ContractorExpenseBudgetFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.contractors.index.title'),
                href: route('contractors.index'),
            },
            {
                title: trans('pages.contractors.edit.title'),
                href: route('contractors.edit', {
                    contractor: route().params.contractor,
                }),
            },
            {
                title: trans('pages.expenses.budgets.index.title'),
                href: route('contractors.expenses.budgets.index', {
                    contractor: route().params.contractor,
                }),
            },
            {
                title: trans('pages.expenses.budgets.edit.title'),
                href: route('contractors.expenses.budgets.edit', {
                    contractor: route().params.contractor,
                    expenseBudget: route().params.expenseBudget,
                }),
            },
        ],
    })),
});

const props = defineProps<ContractorExpenseBudgetFormProps>();

const form = useExpenseBudgetForm(props.expenseBudget);

function submit() {
    const { contractor, expenseBudget } = props;
    form.put(
        route('contractors.expenses.budgets.update', {
            contractor,
            expenseBudget: expenseBudget!,
        }),
    );
}
</script>

<template>
    <Head :title="$t('pages.expenses.budgets.edit.title')" />

    <Form :form :disabled="!expenseBudget?.can_update" @submit="submit()">
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
