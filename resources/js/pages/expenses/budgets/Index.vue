<script setup lang="ts">
import ExpenseCategoryCombobox from '@/components/expense/category/ExpenseCategoryCombobox.vue';
import ExpenseItemCombobox from '@/components/expense/item/ExpenseItemCombobox.vue';
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
import { useAlert, useAuth, useFilters, useFormatter, useLayout, useLayouts } from '@/composables';
import { ExpensesLayout } from '@/layouts';
import {
    ExpenseBudgetIndexProps,
    ExpenseBudgetIndexRequest,
    ExpenseBudgetOneOrManyRequest,
    ExpenseBudgetResource,
} from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { reactiveOmit } from '@vueuse/core';
import { trans, transChoice } from 'laravel-vue-i18n';
import { ArchiveIcon, ArchiveRestoreIcon, CirclePlusIcon, EyeIcon, PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { ref } from 'vue';

defineOptions({
    layout: useLayouts([useLayout(ExpensesLayout, () => ({}))]),
});

const props = defineProps<ExpenseBudgetIndexProps>();

const { abilities } = useAuth();

const alert = useAlert();

const selectedRows = ref<ExpenseBudgetResource[]>([]);
const rowsActions: DataTableRowsAction<ExpenseBudgetResource>[] = [
    {
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (budgets) => budgets.some((expenseBudget) => !expenseBudget.can_trash),
        callback: (budgets) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.expense.budgets.trash.confirm', budgets.length),
                callback: () =>
                    router.delete<ExpenseBudgetOneOrManyRequest>(route('expenses.budgets.trash', {}), {
                        data: { ids: budgets.map(({ id }) => id) },
                        only: ['expenseBudgets'],
                        onSuccess: () => {
                            selectedRows.value = [];
                        },
                    }),
            }),
    },
    {
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (budgets) => budgets.some((expenseBudget) => !expenseBudget.can_restore),
        callback: (budgets) =>
            alert({
                variant: 'success',
                description: transChoice('messages.expense.budgets.restore.confirm', budgets.length),
                callback: () =>
                    router.patch<ExpenseBudgetOneOrManyRequest>(
                        route('expenses.budgets.restore', {}),
                        { ids: budgets.map(({ id }) => id) },
                        {
                            only: ['expenseBudgets'],
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
        disabled: (budgets) => budgets.some((expenseBudget) => !expenseBudget.can_delete),
        callback: (budgets) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.expense.budgets.delete.confirm', budgets.length),
                callback: () =>
                    router.delete<ExpenseBudgetOneOrManyRequest>(route('expenses.budgets.delete', {}), {
                        data: { ids: budgets.map(({ id }) => id) },
                        only: ['expenseBudgets'],
                        onSuccess: () => {
                            selectedRows.value = [];
                        },
                    }),
            }),
    },
];
const rowActions: DataTableRowAction<ExpenseBudgetResource>[] = [
    {
        type: 'href',
        label: trans('view'),
        icon: EyeIcon,
        hidden: (expenseBudget) => expenseBudget.can_update || false,
        disabled: (expenseBudget) => !expenseBudget.can_view,
        href: (expenseBudget) =>
            route('expenses.budgets.edit', {
                expenseBudget,
            }),
    },
    {
        type: 'href',
        label: trans('edit'),
        icon: PencilIcon,
        disabled: (expenseBudget) => !expenseBudget.can_update,
        href: (expenseBudget) =>
            route('expenses.budgets.edit', {
                expenseBudget,
            }),
    },
    {
        type: 'callback',
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (expenseBudget) => !expenseBudget.can_trash,
        callback: (expenseBudget) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.expense.budgets.trash.confirm', 1),
                callback: () =>
                    router.delete<ExpenseBudgetOneOrManyRequest>(
                        route('expenses.budgets.trash', {
                            expenseBudget,
                        }),
                        {
                            only: ['expenseBudgets'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (expenseBudget) => !expenseBudget.can_restore,
        callback: (expenseBudget) =>
            alert({
                variant: 'success',
                description: transChoice('messages.expense.budgets.restore.confirm', 1),
                callback: () =>
                    router.patch<ExpenseBudgetOneOrManyRequest>(
                        route('expenses.budgets.restore', {
                            expenseBudget,
                        }),
                        undefined,
                        {
                            only: ['expenseBudgets'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('delete'),
        icon: Trash2Icon,
        disabled: (expenseBudget) => !expenseBudget.can_delete,
        callback: (expenseBudget) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.expense.budgets.delete.confirm', 1),
                callback: () =>
                    router.delete<ExpenseBudgetOneOrManyRequest>(
                        route('expenses.budgets.delete', {
                            expenseBudget,
                        }),
                        {
                            only: ['expenseBudgets'],
                        },
                    ),
            }),
    },
];

const filters = useFilters<ExpenseBudgetIndexRequest>(
    route('expenses.budgets.index', {}),
    {
        q: props.request.q ?? '',
        page: props.request.page ?? props.expenseBudgets?.meta.current_page,
        per_page: props.request.per_page ?? props.expenseBudgets?.meta.per_page,
        sort_by: props.request.sort_by,
        sort_direction: props.request.sort_direction,
        trashed: props.request.trashed,
        expense_categories: props.request.expense_categories ?? [],
        expense_sub_categories: props.request.expense_sub_categories ?? [],
        expense_items: props.request.expense_items ?? [],
    },
    {
        only: ['expenseBudgets'],
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
                ...reactiveOmit(data, 'expense_categories', 'expense_sub_categories', 'expense_items'),
                expense_category_ids: data.expense_categories?.map(({ id }) => id),
                expense_sub_category_ids: data.expense_sub_categories?.map(({ id }) => id),
                expense_item_ids: data.expense_items?.map(({ id }) => id),
            };
        },
    },
);

const format = useFormatter();
</script>

<template>
    <Head :title="$t('pages.expenses.budgets.index.title')" />

    <Section class="w-full p-0!">
        <SectionContent class="px-0!">
            <DataTable
                v-slot="{ rows }"
                v-model:selected-rows="selectedRows"
                v-model:sort-by="filters.sort_by"
                v-model:sort-direction="filters.sort_direction"
                :data="expenseBudgets"
                :rows-actions
                :row-actions
            >
                <FormContent class="budgets-center flex">
                    <TextInput v-model="filters.q" type="search" />
                    <FiltersSheet
                        :filters
                        :omit="['q', 'page', 'per_page', 'sort_by', 'sort_direction']"
                        :data="['trashedFilters', 'expenseCategories', 'expenseSubCategories', 'expenseItems']"
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
                            <FormField>
                                <FormLabel>
                                    <CapitalizeText>
                                        {{ $t('models.expense.item.name.many') }}
                                    </CapitalizeText>
                                </FormLabel>
                                <FormControl>
                                    <ExpenseItemCombobox v-model="filters.expense_items" multiple />
                                </FormControl>
                            </FormField>
                        </FiltersSheetContent>
                    </FiltersSheet>
                </FormContent>
                <FormContent class="budgets-center flex justify-between" v-if="abilities.expenses.budgets.create">
                    <Button class="ml-auto" as-child>
                        <InertiaLink :href="route('expenses.budgets.create')">
                            <CirclePlusIcon />
                            <CapitalizeText class="max-sm:hidden">
                                {{ $t('pages.expenses.budgets.create.title') }}
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
                            <DataTableHead>
                                {{ $t('models.expense.item.fields.expense_category') }}
                            </DataTableHead>
                            <DataTableHead>
                                {{ $t('models.expense.item.fields.expense_sub_category') }}
                            </DataTableHead>
                            <DataTableSortableHead value="expense_item_id">
                                {{ $t('models.expense.budget.fields.expense_item') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="amount_in_cents">
                                {{ $t('models.expense.budget.fields.amount') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead v-if="filters.trashed" value="deleted_at">
                                {{ $t('models.expense.budget.fields.deleted_at') }}
                            </DataTableSortableHead>
                            <DataTableHead>
                                <DataTableHeadActions />
                            </DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow
                            v-for="expenseBudget in rows"
                            :key="expenseBudget.id"
                            :item="expenseBudget"
                            :class="{ 'bg-destructive/5': expenseBudget.deleted_at }"
                        >
                            <DataTableCell>
                                <DataTableRowCheckbox />
                            </DataTableCell>
                            <DataTableCell>
                                {{ expenseBudget.expense_item?.expense_category?.name }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ expenseBudget.expense_item?.expense_sub_category?.name }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ expenseBudget.expense_item?.name }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ format.price(expenseBudget.amount) }}
                            </DataTableCell>
                            <DataTableCell v-if="filters.trashed">
                                <TrashedBadge :item="expenseBudget" />
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
