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
                title: trans('pages.expenses.charges.create.title'),
                href: route('expenses.charges.create'),
            },
        ],
    })),
});

defineProps<ExpenseChargeFormProps>();

const form = useExpenseChargeForm();

function submit() {
    form.post(route('expenses.charges.create'));
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
