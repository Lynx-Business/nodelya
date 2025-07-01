<script setup lang="ts">
import ExpenseCategoryCombobox from '@/components/expense/category/ExpenseCategoryCombobox.vue';
import ExpenseSubCategoryCombobox from '@/components/expense/sub-category/ExpenseSubCategoryCombobox.vue';
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
    ExpenseItemIndexProps,
    ExpenseItemIndexRequest,
    ExpenseItemOneOrManyRequest,
    ExpenseItemResource,
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
            model: 'item' as const,
        })),
    ]),
});

const props = defineProps<ExpenseItemIndexProps>();

const alert = useAlert();

const selectedRows = ref<ExpenseItemResource[]>([]);
const rowsActions: DataTableRowsAction<ExpenseItemResource>[] = [
    {
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (items) => items.some((expenseItem) => !expenseItem.can_trash),
        callback: (items) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.expense.items.trash.confirm', items.length),
                callback: () =>
                    router.delete<ExpenseItemOneOrManyRequest>(
                        route('teams.expenses.items.trash', { team: props.team, expenseType: props.expenseType }),
                        {
                            data: { ids: items.map(({ id }) => id) },
                            only: ['expenseItems'],
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
        disabled: (items) => items.some((expenseItem) => !expenseItem.can_restore),
        callback: (items) =>
            alert({
                variant: 'success',
                description: transChoice('messages.expense.items.restore.confirm', items.length),
                callback: () =>
                    router.patch<ExpenseItemOneOrManyRequest>(
                        route('teams.expenses.items.restore', {
                            team: props.team,
                            expenseType: props.expenseType,
                        }),
                        { ids: items.map(({ id }) => id) },
                        {
                            only: ['expenseItems'],
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
        disabled: (items) => items.some((expenseItem) => !expenseItem.can_delete),
        callback: (items) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.expense.items.delete.confirm', items.length),
                callback: () =>
                    router.delete<ExpenseItemOneOrManyRequest>(
                        route('teams.expenses.items.delete', { team: props.team, expenseType: props.expenseType }),
                        {
                            data: { ids: items.map(({ id }) => id) },
                            only: ['expenseItems'],
                            onSuccess: () => {
                                selectedRows.value = [];
                            },
                        },
                    ),
            }),
    },
];
const rowActions: DataTableRowAction<ExpenseItemResource>[] = [
    {
        type: 'href',
        label: trans('view'),
        icon: EyeIcon,
        hidden: (expenseItem) => expenseItem.can_update ?? false,
        disabled: (expenseItem) => !expenseItem.can_view,
        href: (expenseItem) =>
            route('teams.expenses.items.edit', {
                team: props.team,
                expenseType: props.expenseType,
                expenseItem,
            }),
    },
    {
        type: 'href',
        label: trans('edit'),
        icon: PencilIcon,
        disabled: (expenseItem) => !expenseItem.can_update,
        href: (expenseItem) =>
            route('teams.expenses.items.edit', {
                team: props.team,
                expenseType: props.expenseType,
                expenseItem,
            }),
    },
    {
        type: 'callback',
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (expenseItem) => !expenseItem.can_trash,
        callback: (expenseItem) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.expense.items.trash.confirm', 1),
                callback: () =>
                    router.delete<ExpenseItemOneOrManyRequest>(
                        route('teams.expenses.items.trash', {
                            team: props.team,
                            expenseType: props.expenseType,
                            expenseItem,
                        }),
                        {
                            only: ['expenseItems'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (expenseItem) => !expenseItem.can_restore,
        callback: (expenseItem) =>
            alert({
                variant: 'success',
                description: transChoice('messages.expense.items.restore.confirm', 1),
                callback: () =>
                    router.patch<ExpenseItemOneOrManyRequest>(
                        route('teams.expenses.items.restore', {
                            team: props.team,
                            expenseType: props.expenseType,
                            expenseItem,
                        }),
                        undefined,
                        {
                            only: ['expenseItems'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('delete'),
        icon: Trash2Icon,
        disabled: (expenseItem) => !expenseItem.can_delete,
        callback: (expenseItem) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.expense.items.delete.confirm', 1),
                callback: () =>
                    router.delete<ExpenseItemOneOrManyRequest>(
                        route('teams.expenses.items.delete', {
                            team: props.team,
                            expenseType: props.expenseType,
                            expenseItem,
                        }),
                        {
                            only: ['expenseItems'],
                        },
                    ),
            }),
    },
];

const filters = useFilters<ExpenseItemIndexRequest>(
    route('teams.expenses.items.index', { team: props.team, expenseType: props.expenseType }),
    {
        q: props.request.q ?? '',
        page: props.request.page ?? props.expenseItems?.meta.current_page,
        per_page: props.request.per_page ?? props.expenseItems?.meta.per_page,
        sort_by: props.request.sort_by,
        sort_direction: props.request.sort_direction,
        trashed: props.request.trashed,
        expense_categories: props.request.expense_categories ?? [],
        expense_sub_categories: props.request.expense_sub_categories ?? [],
    },
    {
        only: ['expenseItems'],
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
                ...reactiveOmit(data, 'expense_categories', 'expense_sub_categories'),
                expense_category_ids: data.expense_categories?.map(({ id }) => id),
                expense_sub_category_ids: data.expense_sub_categories?.map(({ id }) => id),
            };
        },
    },
);
</script>

<template>
    <Head :title="$t('pages.teams.expenses.items.index.title')" />

    <Section class="w-full p-0!">
        <SectionContent class="px-0!">
            <DataTable
                v-slot="{ rows }"
                v-model:selected-rows="selectedRows"
                v-model:sort-by="filters.sort_by"
                v-model:sort-direction="filters.sort_direction"
                :data="expenseItems"
                :rows-actions
                :row-actions
            >
                <FormContent class="flex items-center">
                    <TextInput v-model="filters.q" type="search" />
                    <FiltersSheet
                        :filters
                        :omit="['q', 'page', 'per_page', 'sort_by', 'sort_direction']"
                        :data="['trashedFilters', 'expenseCategories', 'expenseSubCategories']"
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
                            <FormField>
                                <FormLabel>
                                    <CapitalizeText>
                                        {{ $t('models.expense.sub_category.name.many') }}
                                    </CapitalizeText>
                                </FormLabel>
                                <FormControl>
                                    <ExpenseSubCategoryCombobox v-model="filters.expense_sub_categories" multiple />
                                </FormControl>
                            </FormField>
                        </FiltersSheetContent>
                    </FiltersSheet>
                </FormContent>
                <FormContent class="flex items-center justify-between">
                    <Button class="ml-auto" as-child>
                        <InertiaLink :href="route('teams.expenses.items.create', { team, expenseType })">
                            <CirclePlusIcon />
                            <CapitalizeText class="max-sm:hidden">
                                {{ $t('pages.teams.expenses.items.create.title') }}
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
                            <DataTableSortableHead value="expense_category.name">
                                {{ $t('models.expense.category.name.one') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="expense_sub_category.name">
                                {{ $t('models.expense.sub_category.name.one') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="name">
                                {{ $t('models.expense.item.fields.name') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead v-if="filters.trashed" value="deleted_at">
                                {{ $t('models.expense.item.fields.deleted_at') }}
                            </DataTableSortableHead>
                            <DataTableHead>
                                <DataTableHeadActions />
                            </DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow
                            v-for="expenseItem in rows"
                            :key="expenseItem.id"
                            :item="expenseItem"
                            :class="{ 'bg-destructive/5': expenseItem.deleted_at }"
                        >
                            <DataTableCell>
                                <DataTableRowCheckbox />
                            </DataTableCell>
                            <DataTableCell>
                                {{ expenseItem.expense_category?.name }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ expenseItem.expense_sub_category?.name }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ expenseItem.name }}
                            </DataTableCell>
                            <DataTableCell v-if="filters.trashed">
                                <TrashedBadge :item="expenseItem" />
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
