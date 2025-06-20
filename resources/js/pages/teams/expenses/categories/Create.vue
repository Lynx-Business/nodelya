<script setup lang="ts">
import ExpenseCategoryForm from '@/components/expense/category/ExpenseCategoryForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import {
    Section,
    SectionContent,
    SectionDescription,
    SectionFooter,
    SectionHeader,
    SectionTitle,
} from '@/components/ui/custom/section';
import { useExpenseCategoryForm, useLayout, usePageProp } from '@/composables';
import { AppLayout } from '@/layouts';
import { ExpenseCategoryFormProps } from '@/types';
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
                title: trans('pages.teams.expenses.categories.index.title'),
                href: route('teams.expenses.categories.index', {
                    team: route().params.team,
                    expenseType: route().params.expenseType,
                }),
            },
            {
                title: trans('pages.teams.expenses.categories.create.title'),
                href: route('teams.expenses.categories.create', {
                    team: route().params.team,
                    expenseType: route().params.expenseType,
                }),
            },
        ],
    })),
});

const props = defineProps<ExpenseCategoryFormProps>();

const form = useExpenseCategoryForm();

function submit() {
    const { team, expenseType } = props;
    form.post(route('teams.expenses.categories.create', { team, expenseType }));
}
</script>

<template>
    <Head :title="$t('pages.teams.expenses.categories.create.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.teams.expenses.categories.create.title') }}
                </SectionTitle>
                <SectionDescription>
                    {{ $t('pages.teams.expenses.categories.create.description') }}
                </SectionDescription>
            </SectionHeader>
            <SectionContent class="sm:flex">
                <ExpenseCategoryForm autofocus />
            </SectionContent>
            <SectionFooter>
                <FormSubmitButton />
            </SectionFooter>
        </Section>
    </Form>
</template>
