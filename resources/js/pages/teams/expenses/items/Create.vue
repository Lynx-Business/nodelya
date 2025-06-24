<script setup lang="ts">
import ExpenseItemForm from '@/components/expense/item/ExpenseItemForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import {
    Section,
    SectionContent,
    SectionDescription,
    SectionFooter,
    SectionHeader,
    SectionTitle,
} from '@/components/ui/custom/section';
import { useExpenseItemForm, useLayout, usePageProp } from '@/composables';
import { AppLayout } from '@/layouts';
import { ExpenseItemFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.teams.index.title'),
                href: route('teams.index'),
            },
            {
                title: usePageProp<string>('team.name', route().params.team).value,
                href: route('teams.edit', { team: route().params.team }),
            },
            {
                title: trans('pages.teams.expenses.items.index.title'),
                href: route('teams.expenses.items.index', {
                    team: route().params.team,
                    expenseType: route().params.expenseType,
                }),
            },
            {
                title: trans('pages.teams.expenses.items.create.title'),
                href: route('teams.expenses.items.create', {
                    team: route().params.team,
                    expenseType: route().params.expenseType,
                }),
            },
        ],
    })),
});

const props = defineProps<ExpenseItemFormProps>();

const form = useExpenseItemForm();

function submit() {
    const { team, expenseType } = props;
    form.post(route('teams.expenses.items.create', { team, expenseType }));
}
</script>

<template>
    <Head :title="$t('pages.teams.expenses.items.create.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.teams.expenses.items.create.title') }}
                </SectionTitle>
                <SectionDescription>
                    {{ $t('pages.teams.expenses.items.create.description') }}
                </SectionDescription>
            </SectionHeader>
            <SectionContent class="sm:flex">
                <ExpenseItemForm autofocus />
            </SectionContent>
            <SectionFooter>
                <FormSubmitButton />
            </SectionFooter>
        </Section>
    </Form>
</template>
