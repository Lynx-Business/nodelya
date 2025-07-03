<script setup lang="ts">
import AccountingPeriodCombobox from '@/components/accounting-period/AccountingPeriodCombobox.vue';
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
import { useAlert, useAuth, useFilters, useFormatter, useLayout } from '@/composables';
import { EmployeesFormLayout } from '@/layouts';
import {
    EmployeeExpenseBudgetIndexProps,
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
    layout: useLayout(EmployeesFormLayout, () => ({})),
});

const props = defineProps<EmployeeExpenseBudgetIndexProps>();

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
                    router.delete<ExpenseBudgetOneOrManyRequest>(
                        route('employees.expenses.budgets.trash', {
                            employee: props.employee,
                        }),
                        {
                            data: { ids: budgets.map(({ id }) => id) },
                            only: ['expenseBudgets'],
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
        disabled: (budgets) => budgets.some((expenseBudget) => !expenseBudget.can_restore),
        callback: (budgets) =>
            alert({
                variant: 'success',
                description: transChoice('messages.expense.budgets.restore.confirm', budgets.length),
                callback: () =>
                    router.patch<ExpenseBudgetOneOrManyRequest>(
                        route('employees.expenses.budgets.restore', {
                            employee: props.employee,
                        }),
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
                    router.delete<ExpenseBudgetOneOrManyRequest>(
                        route('employees.expenses.budgets.delete', {
                            employee: props.employee,
                        }),
                        {
                            data: { ids: budgets.map(({ id }) => id) },
                            only: ['expenseBudgets'],
                            onSuccess: () => {
                                selectedRows.value = [];
                            },
                        },
                    ),
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
            route('employees.expenses.budgets.edit', {
                employee: props.employee,
                expenseBudget,
            }),
    },
    {
        type: 'href',
        label: trans('edit'),
        icon: PencilIcon,
        hidden: (expenseBudget) => !expenseBudget.can_update,
        href: (expenseBudget) =>
            route('employees.expenses.budgets.edit', {
                employee: props.employee,
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
                        route('employees.expenses.budgets.trash', {
                            employee: props.employee,
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
                        route('employees.expenses.budgets.restore', {
                            employee: props.employee,
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
                        route('employees.expenses.budgets.delete', {
                            employee: props.employee,
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
    route('employees.expenses.budgets.index', {
        employee: props.employee,
    }),
    {
        q: props.request.q ?? '',
        page: props.request.page ?? props.expenseBudgets?.meta.current_page,
        per_page: props.request.per_page ?? props.expenseBudgets?.meta.per_page,
        sort_by: props.request.sort_by,
        sort_direction: props.request.sort_direction,
        trashed: props.request.trashed,
        accounting_period: props.request.accounting_period,
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
                ...reactiveOmit(
                    data,
                    'accounting_period',
                    'expense_categories',
                    'expense_sub_categories',
                    'expense_items',
                ),
                accounting_period_id: data.accounting_period?.id,
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
                <FormContent class="grid items-center sm:flex">
                    <TextInput v-model="filters.q" type="search" />
                    <AccountingPeriodCombobox v-model="filters.accounting_period" required />
                    <FiltersSheet
                        :filters
                        :omit="[
                            'q',
                            'page',
                            'per_page',
                            'sort_by',
                            'sort_direction',
                            'accounting_period_id',
                            'accounting_period',
                        ]"
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
                <FormContent class="flex items-center justify-between" v-if="abilities.expenses.budgets.create">
                    <Button class="ml-auto" as-child>
                        <InertiaLink
                            :href="
                                route('employees.expenses.budgets.create', {
                                    employee,
                                })
                            "
                        >
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
                                <div>
                                    {{ expenseBudget.expense_item?.expense_category?.name }}
                                </div>
                                <div
                                    class="text-muted-foreground text-xs"
                                    v-if="expenseBudget.expense_item?.expense_sub_category?.name"
                                >
                                    {{ expenseBudget.expense_item?.expense_sub_category?.name }}
                                </div>
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
