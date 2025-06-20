<script setup lang="ts">
import ExpenseSubCategoryForm from '@/components/expense/sub-category/ExpenseSubCategoryForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import {
    Section,
    SectionContent,
    SectionDescription,
    SectionFooter,
    SectionHeader,
    SectionTitle,
} from '@/components/ui/custom/section';
import { useExpenseSubCategoryForm, useLayout, usePageProp } from '@/composables';
import { AppLayout } from '@/layouts';
import { ExpenseSubCategoryFormProps } from '@/types';
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
                title: trans('pages.teams.expenses.sub_categories.index.title'),
                href: route('teams.expenses.sub-categories.index', {
                    team: route().params.team,
                    expenseType: route().params.expenseType,
                }),
            },
            {
                title: trans('pages.teams.expenses.sub_categories.edit.title'),
                href: route('teams.expenses.sub-categories.edit', {
                    team: route().params.team,
                    expenseType: route().params.expenseType,
                    expenseSubCategory: route().params.expenseSubCategory,
                }),
            },
        ],
    })),
});

const props = defineProps<ExpenseSubCategoryFormProps>();

const form = useExpenseSubCategoryForm(props.expenseSubCategory);

function submit() {
    const { team, expenseType, expenseSubCategory } = props;
    form.put(
        route('teams.expenses.sub-categories.update', { team, expenseType, expenseSubCategory: expenseSubCategory! }),
    );
}
</script>

<template>
    <Head :title="$t('pages.teams.expenses.sub_categories.edit.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.teams.expenses.sub_categories.edit.title') }}
                </SectionTitle>
                <SectionDescription>
                    {{ $t('pages.teams.expenses.sub_categories.edit.description') }}
                </SectionDescription>
            </SectionHeader>
            <SectionContent class="sm:flex">
                <ExpenseSubCategoryForm autofocus />
            </SectionContent>
            <SectionFooter>
                <FormSubmitButton />
            </SectionFooter>
        </Section>
    </Form>
</template>
