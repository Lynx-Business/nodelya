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
import { ContractorExpenseChargeFormProps } from '@/types';
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
                title: trans('pages.expenses.charges.index.title'),
                href: route('contractors.expenses.charges.index', {
                    contractor: route().params.contractor,
                }),
            },
            {
                title: trans('pages.expenses.charges.edit.title'),
                href: route('contractors.expenses.charges.edit', {
                    contractor: route().params.contractor,
                    expenseCharge: route().params.expenseCharge,
                }),
            },
        ],
    })),
});

const props = defineProps<ContractorExpenseChargeFormProps>();

const form = useExpenseChargeForm(props.expenseCharge);

function submit() {
    const { contractor, expenseCharge } = props;
    form.put(
        route('contractors.expenses.charges.update', {
            contractor,
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
