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
                title: trans('pages.teams.expenses.items.edit.title'),
                href: route('teams.expenses.items.edit', {
                    team: route().params.team,
                    expenseType: route().params.expenseType,
                    expenseItem: route().params.expenseItem,
                }),
            },
        ],
    })),
});

const props = defineProps<ExpenseItemFormProps>();

const form = useExpenseItemForm(props.expenseItem);

function submit() {
    const { team, expenseType, expenseItem } = props;
    form.put(route('teams.expenses.items.update', { team, expenseType, expenseItem: expenseItem! }));
}
</script>

<template>
    <Head :title="$t('pages.teams.expenses.items.edit.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.teams.expenses.items.edit.title') }}
                </SectionTitle>
                <SectionDescription>
                    {{ $t('pages.teams.expenses.items.edit.description') }}
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
