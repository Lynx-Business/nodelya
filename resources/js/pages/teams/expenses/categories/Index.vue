<script setup lang="ts">
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
    ExpenseCategoryIndexProps,
    ExpenseCategoryIndexRequest,
    ExpenseCategoryOneOrManyRequest,
    ExpenseCategoryResource,
} from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { trans, transChoice } from 'laravel-vue-i18n';
import { ArchiveIcon, ArchiveRestoreIcon, CirclePlusIcon, EyeIcon, PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { ref } from 'vue';

defineOptions({
    layout: useLayouts([
        useLayout(TeamsFormLayout, () => ({})),
        useLayout(TeamsFormExpensesLayout, () => ({
            model: 'category' as const,
        })),
    ]),
});

const props = defineProps<ExpenseCategoryIndexProps>();

const alert = useAlert();

const selectedRows = ref<ExpenseCategoryResource[]>([]);
const rowsActions: DataTableRowsAction<ExpenseCategoryResource>[] = [
    {
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (items) => items.some((expenseCategory) => !expenseCategory.can_trash),
        callback: (items) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.expense.categories.trash.confirm', items.length),
                callback: () =>
                    router.delete<ExpenseCategoryOneOrManyRequest>(
                        route('teams.expenses.categories.trash', { team: props.team, expenseType: props.expenseType }),
                        {
                            data: { ids: items.map(({ id }) => id) },
                            only: ['expenseCategories'],
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
        disabled: (items) => items.some((expenseCategory) => !expenseCategory.can_restore),
        callback: (items) =>
            alert({
                variant: 'success',
                description: transChoice('messages.expense.categories.restore.confirm', items.length),
                callback: () =>
                    router.patch<ExpenseCategoryOneOrManyRequest>(
                        route('teams.expenses.categories.restore', {
                            team: props.team,
                            expenseType: props.expenseType,
                        }),
                        { ids: items.map(({ id }) => id) },
                        {
                            only: ['expenseCategories'],
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
        disabled: (items) => items.some((expenseCategory) => !expenseCategory.can_delete),
        callback: (items) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.expense.categories.delete.confirm', items.length),
                callback: () =>
                    router.delete<ExpenseCategoryOneOrManyRequest>(
                        route('teams.expenses.categories.delete', { team: props.team, expenseType: props.expenseType }),
                        {
                            data: { ids: items.map(({ id }) => id) },
                            only: ['expenseCategories'],
                            onSuccess: () => {
                                selectedRows.value = [];
                            },
                        },
                    ),
            }),
    },
];
const rowActions: DataTableRowAction<ExpenseCategoryResource>[] = [
    {
        type: 'href',
        label: trans('view'),
        icon: EyeIcon,
        hidden: (expenseCategory) => expenseCategory.can_update ?? false,
        disabled: (expenseCategory) => !expenseCategory.can_view,
        href: (expenseCategory) =>
            route('teams.expenses.categories.edit', {
                team: props.team,
                expenseType: props.expenseType,
                expenseCategory,
            }),
    },
    {
        type: 'href',
        label: trans('edit'),
        icon: PencilIcon,
        disabled: (expenseCategory) => !expenseCategory.can_update,
        href: (expenseCategory) =>
            route('teams.expenses.categories.edit', {
                team: props.team,
                expenseType: props.expenseType,
                expenseCategory,
            }),
    },
    {
        type: 'callback',
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (expenseCategory) => !expenseCategory.can_trash,
        callback: (expenseCategory) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.expense.categories.trash.confirm', 1),
                callback: () =>
                    router.delete<ExpenseCategoryOneOrManyRequest>(
                        route('teams.expenses.categories.trash', {
                            team: props.team,
                            expenseType: props.expenseType,
                            expenseCategory,
                        }),
                        {
                            only: ['expenseCategories'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (expenseCategory) => !expenseCategory.can_restore,
        callback: (expenseCategory) =>
            alert({
                variant: 'success',
                description: transChoice('messages.expense.categories.restore.confirm', 1),
                callback: () =>
                    router.patch<ExpenseCategoryOneOrManyRequest>(
                        route('teams.expenses.categories.restore', {
                            team: props.team,
                            expenseType: props.expenseType,
                            expenseCategory,
                        }),
                        undefined,
                        {
                            only: ['expenseCategories'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('delete'),
        icon: Trash2Icon,
        disabled: (expenseCategory) => !expenseCategory.can_delete,
        callback: (expenseCategory) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.expense.categories.delete.confirm', 1),
                callback: () =>
                    router.delete<ExpenseCategoryOneOrManyRequest>(
                        route('teams.expenses.categories.delete', {
                            team: props.team,
                            expenseType: props.expenseType,
                            expenseCategory,
                        }),
                        {
                            only: ['expenseCategories'],
                        },
                    ),
            }),
    },
];

const filters = useFilters<ExpenseCategoryIndexRequest>(
    route('teams.expenses.categories.index', { team: props.team, expenseType: props.expenseType }),
    {
        q: props.request.q ?? '',
        page: props.request.page ?? props.expenseCategories?.meta.current_page,
        per_page: props.request.per_page ?? props.expenseCategories?.meta.per_page,
        sort_by: props.request.sort_by,
        sort_direction: props.request.sort_direction,
        trashed: props.request.trashed,
    },
    {
        only: ['expenseCategories'],
        immediate: true,
        debounceReload(keys) {
            return !keys.includes('page') && !keys.includes('per_page');
        },
        onReload(keys) {
            if (!keys.includes('page')) {
                filters.page = 1;
            }
        },
    },
);
</script>

<template>
    <Head :title="$t('pages.teams.expenses.categories.index.title')" />

    <Section class="w-full p-0!">
        <SectionContent class="px-0!">
            <DataTable
                v-slot="{ rows }"
                v-model:selected-rows="selectedRows"
                v-model:sort-by="filters.sort_by"
                v-model:sort-direction="filters.sort_direction"
                :data="expenseCategories"
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
                        </FiltersSheetContent>
                    </FiltersSheet>
                </FormContent>
                <FormContent class="flex items-center justify-between">
                    <Button class="ml-auto" as-child>
                        <InertiaLink :href="route('teams.expenses.categories.create', { team, expenseType })">
                            <CirclePlusIcon />
                            <CapitalizeText class="max-sm:hidden">
                                {{ $t('pages.teams.expenses.categories.create.title') }}
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
                                {{ $t('models.expense.category.fields.name') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead v-if="filters.trashed" value="deleted_at">
                                {{ $t('models.expense.category.fields.deleted_at') }}
                            </DataTableSortableHead>
                            <DataTableHead>
                                <DataTableHeadActions />
                            </DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow
                            v-for="expenseCategory in rows"
                            :key="expenseCategory.id"
                            :item="expenseCategory"
                            :class="{ 'bg-destructive/5': expenseCategory.deleted_at }"
                        >
                            <DataTableCell>
                                <DataTableRowCheckbox />
                            </DataTableCell>
                            <DataTableCell>
                                {{ expenseCategory.name }}
                            </DataTableCell>
                            <DataTableCell v-if="filters.trashed">
                                <TrashedBadge :item="expenseCategory" />
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
