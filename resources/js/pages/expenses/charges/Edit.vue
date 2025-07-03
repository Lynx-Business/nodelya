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
import { ExpenseChargeFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('enums.expense.type.general'),
                href: route('expenses.charges.index'),
            },
            {
                title: trans('pages.expenses.charges.edit.title'),
                href: route('expenses.charges.edit', {
                    expenseCharge: route().params.expenseCharge,
                }),
            },
        ],
    })),
});

const props = defineProps<ExpenseChargeFormProps>();

const form = useExpenseChargeForm(props.expenseCharge);

function submit() {
    const { expenseCharge } = props;
    form.put(route('expenses.charges.update', { expenseCharge: expenseCharge! }));
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
