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
import { ContractorsFormLayout } from '@/layouts';
import {
    ContractorExpenseChargeIndexProps,
    ExpenseChargeIndexRequest,
    ExpenseChargeOneOrManyRequest,
    ExpenseChargeResource,
} from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { reactiveOmit } from '@vueuse/core';
import { trans, transChoice } from 'laravel-vue-i18n';
import { ArchiveIcon, ArchiveRestoreIcon, CirclePlusIcon, EyeIcon, PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { ref } from 'vue';

defineOptions({
    layout: useLayout(ContractorsFormLayout, () => ({})),
});

const props = defineProps<ContractorExpenseChargeIndexProps>();

const { abilities } = useAuth();

const alert = useAlert();

const selectedRows = ref<ExpenseChargeResource[]>([]);
const rowsActions: DataTableRowsAction<ExpenseChargeResource>[] = [
    {
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (charges) => charges.some((expenseCharge) => !expenseCharge.can_trash),
        callback: (charges) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.expense.charges.trash.confirm', charges.length),
                callback: () =>
                    router.delete<ExpenseChargeOneOrManyRequest>(
                        route('contractors.expenses.charges.trash', {
                            contractor: props.contractor,
                        }),
                        {
                            data: { ids: charges.map(({ id }) => id) },
                            only: ['expenseCharges'],
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
        disabled: (charges) => charges.some((expenseCharge) => !expenseCharge.can_restore),
        callback: (charges) =>
            alert({
                variant: 'success',
                description: transChoice('messages.expense.charges.restore.confirm', charges.length),
                callback: () =>
                    router.patch<ExpenseChargeOneOrManyRequest>(
                        route('contractors.expenses.charges.restore', {
                            contractor: props.contractor,
                        }),
                        { ids: charges.map(({ id }) => id) },
                        {
                            only: ['expenseCharges'],
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
        disabled: (charges) => charges.some((expenseCharge) => !expenseCharge.can_delete),
        callback: (charges) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.expense.charges.delete.confirm', charges.length),
                callback: () =>
                    router.delete<ExpenseChargeOneOrManyRequest>(
                        route('contractors.expenses.charges.delete', {
                            contractor: props.contractor,
                        }),
                        {
                            data: { ids: charges.map(({ id }) => id) },
                            only: ['expenseCharges'],
                            onSuccess: () => {
                                selectedRows.value = [];
                            },
                        },
                    ),
            }),
    },
];
const rowActions: DataTableRowAction<ExpenseChargeResource>[] = [
    {
        type: 'href',
        label: trans('view'),
        icon: EyeIcon,
        hidden: (expenseCharge) => expenseCharge.can_update || false,
        disabled: (expenseCharge) => !expenseCharge.can_view,
        href: (expenseCharge) =>
            route('contractors.expenses.charges.edit', {
                contractor: props.contractor,
                expenseCharge,
            }),
    },
    {
        type: 'href',
        label: trans('edit'),
        icon: PencilIcon,
        hidden: (expenseCharge) => !expenseCharge.can_update,
        href: (expenseCharge) =>
            route('contractors.expenses.charges.edit', {
                contractor: props.contractor,
                expenseCharge,
            }),
    },
    {
        type: 'callback',
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (expenseCharge) => !expenseCharge.can_trash,
        callback: (expenseCharge) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.expense.charges.trash.confirm', 1),
                callback: () =>
                    router.delete<ExpenseChargeOneOrManyRequest>(
                        route('contractors.expenses.charges.trash', {
                            contractor: props.contractor,
                            expenseCharge,
                        }),
                        {
                            only: ['expenseCharges'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (expenseCharge) => !expenseCharge.can_restore,
        callback: (expenseCharge) =>
            alert({
                variant: 'success',
                description: transChoice('messages.expense.charges.restore.confirm', 1),
                callback: () =>
                    router.patch<ExpenseChargeOneOrManyRequest>(
                        route('contractors.expenses.charges.restore', {
                            contractor: props.contractor,
                            expenseCharge,
                        }),
                        undefined,
                        {
                            only: ['expenseCharges'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('delete'),
        icon: Trash2Icon,
        disabled: (expenseCharge) => !expenseCharge.can_delete,
        callback: (expenseCharge) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.expense.charges.delete.confirm', 1),
                callback: () =>
                    router.delete<ExpenseChargeOneOrManyRequest>(
                        route('contractors.expenses.charges.delete', {
                            contractor: props.contractor,
                            expenseCharge,
                        }),
                        {
                            only: ['expenseCharges'],
                        },
                    ),
            }),
    },
];

const filters = useFilters<ExpenseChargeIndexRequest>(
    route('contractors.expenses.charges.index', {
        contractor: props.contractor,
    }),
    {
        q: props.request.q ?? '',
        page: props.request.page ?? props.expenseCharges?.meta.current_page,
        per_page: props.request.per_page ?? props.expenseCharges?.meta.per_page,
        sort_by: props.request.sort_by,
        sort_direction: props.request.sort_direction,
        trashed: props.request.trashed,
        accounting_period: props.request.accounting_period,
        expense_categories: props.request.expense_categories ?? [],
        expense_sub_categories: props.request.expense_sub_categories ?? [],
        expense_items: props.request.expense_items ?? [],
    },
    {
        only: ['expenseCharges'],
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
    <Head :title="$t('pages.expenses.charges.index.title')" />

    <Section class="w-full p-0!">
        <SectionContent class="px-0!">
            <DataTable
                v-slot="{ rows }"
                v-model:selected-rows="selectedRows"
                v-model:sort-by="filters.sort_by"
                v-model:sort-direction="filters.sort_direction"
                :data="expenseCharges"
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
                <FormContent class="flex items-center justify-between" v-if="abilities.expenses.charges.create">
                    <Button class="ml-auto" as-child>
                        <InertiaLink
                            :href="
                                route('contractors.expenses.charges.create', {
                                    contractor,
                                })
                            "
                        >
                            <CirclePlusIcon />
                            <CapitalizeText class="max-sm:hidden">
                                {{ $t('pages.expenses.charges.create.title') }}
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
                                {{ $t('models.expense.charge.fields.expense_item') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="charged_at">
                                {{ $t('models.expense.charge.fields.charged_at') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="amount_in_cents">
                                {{ $t('models.expense.charge.fields.amount') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead v-if="filters.trashed" value="deleted_at">
                                {{ $t('models.expense.charge.fields.deleted_at') }}
                            </DataTableSortableHead>
                            <DataTableHead>
                                <DataTableHeadActions />
                            </DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow
                            v-for="expenseCharge in rows"
                            :key="expenseCharge.id"
                            :item="expenseCharge"
                            :class="{ 'bg-destructive/5': expenseCharge.deleted_at }"
                        >
                            <DataTableCell>
                                <DataTableRowCheckbox />
                            </DataTableCell>
                            <DataTableCell>
                                <div>
                                    {{ expenseCharge.expense_item?.expense_category?.name }}
                                </div>
                                <div
                                    class="text-muted-foreground text-xs"
                                    v-if="expenseCharge.expense_item?.expense_sub_category?.name"
                                >
                                    {{ expenseCharge.expense_item?.expense_sub_category?.name }}
                                </div>
                            </DataTableCell>
                            <DataTableCell>
                                {{ expenseCharge.expense_item?.name }}
                            </DataTableCell>
                            <DataTableCell>
                                <CapitalizeText>
                                    {{ format.date(expenseCharge.charged_at, { month: 'long', year: '2-digit' }) }}
                                </CapitalizeText>
                            </DataTableCell>
                            <DataTableCell>
                                {{ format.price(expenseCharge.amount) }}
                            </DataTableCell>
                            <DataTableCell v-if="filters.trashed">
                                <TrashedBadge :item="expenseCharge" />
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
