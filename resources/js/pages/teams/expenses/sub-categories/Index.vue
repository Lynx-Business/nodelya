<script setup lang="ts">
import ExpenseCategoryCombobox from '@/components/expense/category/ExpenseCategoryCombobox.vue';
import TrashedBadge from '@/components/trash/TrashedBadge.vue';
import TrashedFilterCombobox from '@/components/trash/TrashedFilterCombobox.vue';
import { Button } from '@/components/ui/button';
import {
    DataTable,
    DataTableBody,
    DataTableCell,
    DataTableContent,
    DataTableHead,
    DataTableHeadActions,
    DataTableHeader,
    DataTablePagination,
    DataTableRow,
    DataTableRowAction,
    DataTableRowActions,
    DataTableRowCheckbox,
    DataTableRowsAction,
    DataTableRowsCheckbox,
    DataTableSortableHead,
} from '@/components/ui/custom/data-table';
import { FiltersSheet, FiltersSheetContent, FiltersSheetTrigger } from '@/components/ui/custom/filters';
import { FormContent, FormControl, FormField, FormLabel } from '@/components/ui/custom/form';
import { TextInput } from '@/components/ui/custom/input';
import { InertiaLink } from '@/components/ui/custom/link';
import { Section, SectionContent } from '@/components/ui/custom/section';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { useAlert, useFilters, useLayout, useLayouts } from '@/composables';
import { TeamsFormExpensesLayout, TeamsFormLayout } from '@/layouts';
import {
    ExpenseSubCategoryIndexProps,
    ExpenseSubCategoryIndexRequest,
    ExpenseSubCategoryIndexResource,
    ExpenseSubCategoryOneOrManyRequest,
} from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { reactiveOmit } from '@vueuse/core';
import { trans, transChoice } from 'laravel-vue-i18n';
import { ArchiveIcon, ArchiveRestoreIcon, CirclePlusIcon, EyeIcon, PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { ref } from 'vue';

defineOptions({
    layout: useLayouts([
        useLayout(TeamsFormLayout, () => ({})),
        useLayout(TeamsFormExpensesLayout, () => ({
            model: 'sub-category' as const,
        })),
    ]),
});

const props = defineProps<ExpenseSubCategoryIndexProps>();

const alert = useAlert();

const selectedRows = ref<ExpenseSubCategoryIndexResource[]>([]);
const rowsActions: DataTableRowsAction<ExpenseSubCategoryIndexResource>[] = [
    {
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (items) => items.some((expenseSubCategory) => !expenseSubCategory.can_trash),
        callback: (items) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.expense.sub_categories.trash.confirm', items.length),
                callback: () =>
                    router.delete<ExpenseSubCategoryOneOrManyRequest>(
                        route('teams.expenses.sub-categories.trash', {
                            team: props.team,
                            expenseType: props.expenseType,
                        }),
                        {
                            data: { ids: items.map(({ id }) => id) },
                            only: ['expenseSubCategories'],
                            onSuccess: () => {
                                selectedRows.value = [];
                            },
                        },
                    ),
            }),
    },
    {
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (items) => items.some((expenseSubCategory) => !expenseSubCategory.can_restore),
        callback: (items) =>
            alert({
                variant: 'success',
                description: transChoice('messages.expense.sub_categories.restore.confirm', items.length),
                callback: () =>
                    router.patch<ExpenseSubCategoryOneOrManyRequest>(
                        route('teams.expenses.sub-categories.restore', {
                            team: props.team,
                            expenseType: props.expenseType,
                        }),
                        { ids: items.map(({ id }) => id) },
                        {
                            only: ['expenseSubCategories'],
                            onSuccess: () => {
                                selectedRows.value = [];
                            },
                        },
                    ),
            }),
    },
    {
        label: trans('delete'),
        icon: Trash2Icon,
        disabled: (items) => items.some((expenseSubCategory) => !expenseSubCategory.can_delete),
        callback: (items) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.expense.sub_categories.delete.confirm', items.length),
                callback: () =>
                    router.delete<ExpenseSubCategoryOneOrManyRequest>(
                        route('teams.expenses.sub-categories.delete', {
                            team: props.team,
                            expenseType: props.expenseType,
                        }),
                        {
                            data: { ids: items.map(({ id }) => id) },
                            only: ['expenseSubCategories'],
                            onSuccess: () => {
                                selectedRows.value = [];
                            },
                        },
                    ),
            }),
    },
];
const rowActions: DataTableRowAction<ExpenseSubCategoryIndexResource>[] = [
    {
        type: 'href',
        label: trans('view'),
        icon: EyeIcon,
        hidden: (expenseSubCategory) => expenseSubCategory.can_update,
        disabled: (expenseSubCategory) => !expenseSubCategory.can_view,
        href: (expenseSubCategory) =>
            route('teams.expenses.sub-categories.edit', {
                team: props.team,
                expenseType: props.expenseType,
                expenseSubCategory,
            }),
    },
    {
        type: 'href',
        label: trans('edit'),
        icon: PencilIcon,
        disabled: (expenseSubCategory) => !expenseSubCategory.can_update,
        href: (expenseSubCategory) =>
            route('teams.expenses.sub-categories.edit', {
                team: props.team,
                expenseType: props.expenseType,
                expenseSubCategory,
            }),
    },
    {
        type: 'callback',
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (expenseSubCategory) => !expenseSubCategory.can_trash,
        callback: (expenseSubCategory) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.expense.sub_categories.trash.confirm', 1),
                callback: () =>
                    router.delete<ExpenseSubCategoryOneOrManyRequest>(
                        route('teams.expenses.sub-categories.trash', {
                            team: props.team,
                            expenseType: props.expenseType,
                            expenseSubCategory,
                        }),
                        {
                            only: ['expenseSubCategories'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (expenseSubCategory) => !expenseSubCategory.can_restore,
        callback: (expenseSubCategory) =>
            alert({
                variant: 'success',
                description: transChoice('messages.expense.sub_categories.restore.confirm', 1),
                callback: () =>
                    router.patch<ExpenseSubCategoryOneOrManyRequest>(
                        route('teams.expenses.sub-categories.restore', {
                            team: props.team,
                            expenseType: props.expenseType,
                            expenseSubCategory,
                        }),
                        undefined,
                        {
                            only: ['expenseSubCategories'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('delete'),
        icon: Trash2Icon,
        disabled: (expenseSubCategory) => !expenseSubCategory.can_delete,
        callback: (expenseSubCategory) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.expense.sub_categories.delete.confirm', 1),
                callback: () =>
                    router.delete<ExpenseSubCategoryOneOrManyRequest>(
                        route('teams.expenses.sub-categories.delete', {
                            team: props.team,
                            expenseType: props.expenseType,
                            expenseSubCategory,
                        }),
                        {
                            only: ['expenseSubCategories'],
                        },
                    ),
            }),
    },
];

const filters = useFilters<ExpenseSubCategoryIndexRequest>(
    route('teams.expenses.sub-categories.index', { team: props.team, expenseType: props.expenseType }),
    {
        q: props.request.q ?? '',
        page: props.request.page ?? props.expenseSubCategories?.meta.current_page,
        per_page: props.request.per_page ?? props.expenseSubCategories?.meta.per_page,
        sort_by: props.request.sort_by,
        sort_direction: props.request.sort_direction,
        trashed: props.request.trashed,
        expense_categories: props.request.expense_categories,
    },
    {
        only: ['expenseSubCategories'],
        immediate: true,
        debounceReload(keys) {
            return !keys.includes('page') && !keys.includes('per_page');
        },
        onReload(keys) {
            if (!keys.includes('page')) {
                filters.page = 1;
            }
        },
        transform(data) {
            return {
                ...reactiveOmit(data, 'expense_categories'),
                expense_category_ids: data.expense_categories?.map(({ id }) => id),
            };
        },
    },
);
</script>

<template>
    <Head :title="$t('pages.teams.expenses.sub_categories.index.title')" />

    <Section class="w-full p-0!">
        <SectionContent class="px-0!">
            <DataTable
                v-slot="{ rows }"
                v-model:selected-rows="selectedRows"
                v-model:sort-by="filters.sort_by"
                v-model:sort-direction="filters.sort_direction"
                :data="expenseSubCategories"
                :rows-actions
                :row-actions
            >
                <FormContent class="flex items-center">
                    <TextInput v-model="filters.q" type="search" />
                    <FiltersSheet
                        :filters
                        :omit="['q', 'page', 'per_page', 'sort_by', 'sort_direction']"
                        :data="['trashedFilters']"
                    >
                        <FiltersSheetTrigger />
                        <FiltersSheetContent>
                            <FormField>
                                <FormLabel>
                                    <CapitalizeText>
                                        {{ $t('trashed') }}
                                    </CapitalizeText>
                                </FormLabel>
                                <FormControl>
                                    <TrashedFilterCombobox v-model="filters.trashed" />
                                </FormControl>
                            </FormField>
                            <FormField>
                                <FormLabel>
                                    <CapitalizeText>
                                        {{ $t('models.expense.category.name.many') }}
                                    </CapitalizeText>
                                </FormLabel>
                                <FormControl>
                                    <ExpenseCategoryCombobox v-model="filters.expense_categories" multiple />
                                </FormControl>
                            </FormField>
                        </FiltersSheetContent>
                    </FiltersSheet>
                </FormContent>
                <FormContent class="flex items-center justify-between">
                    <Button class="ml-auto" as-child>
                        <InertiaLink :href="route('teams.expenses.sub-categories.create', { team, expenseType })">
                            <CirclePlusIcon />
                            <CapitalizeText class="max-sm:hidden">
                                {{ $t('pages.teams.expenses.sub_categories.create.title') }}
                            </CapitalizeText>
                        </InertiaLink>
                    </Button>
                </FormContent>
                <DataTableContent tab="table">
                    <DataTableHeader>
                        <DataTableRow>
                            <DataTableHead>
                                <DataTableRowsCheckbox />
                            </DataTableHead>
                            <DataTableSortableHead value="name">
                                {{ $t('models.expense.sub_category.fields.expense_category') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="name">
                                {{ $t('models.expense.sub_category.fields.name') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead v-if="filters.trashed" value="deleted_at">
                                {{ $t('models.expense.sub_category.fields.deleted_at') }}
                            </DataTableSortableHead>
                            <DataTableHead>
                                <DataTableHeadActions />
                            </DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow
                            v-for="expenseSubCategory in rows"
                            :key="expenseSubCategory.id"
                            :item="expenseSubCategory"
                            :class="{ 'bg-destructive/5': expenseSubCategory.deleted_at }"
                        >
                            <DataTableCell>
                                <DataTableRowCheckbox />
                            </DataTableCell>
                            <DataTableCell>
                                {{ expenseSubCategory.expense_category.name }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ expenseSubCategory.name }}
                            </DataTableCell>
                            <DataTableCell v-if="filters.trashed">
                                <TrashedBadge :item="expenseSubCategory" />
                            </DataTableCell>
                            <DataTableCell>
                                <DataTableRowActions />
                            </DataTableCell>
                        </DataTableRow>
                    </DataTableBody>
                </DataTableContent>
                <DataTablePagination v-model:page="filters.page" v-model:per-page="filters.per_page" />
            </DataTable>
        </SectionContent>
    </Section>
</template>
