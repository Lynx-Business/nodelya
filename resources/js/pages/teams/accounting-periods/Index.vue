<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { EnumCombobox } from '@/components/ui/custom/combobox';
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
    DataTableRowsActions,
    DataTableRowsCheckbox,
    DataTableSortableHead,
} from '@/components/ui/custom/data-table';
import { FiltersSheet, FiltersSheetContent, FiltersSheetTrigger } from '@/components/ui/custom/filters';
import { FormContent, FormControl, FormField, FormLabel } from '@/components/ui/custom/form';
import { TextInput } from '@/components/ui/custom/input';
import { InertiaLink } from '@/components/ui/custom/link';
import { Section, SectionContent } from '@/components/ui/custom/section';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { useAlert, useFilters, useFormatter, useLayout } from '@/composables';
import { TeamsFormLayout } from '@/layouts';
import {
    AccountingPeriodIndexProps,
    AccountingPeriodIndexRequest,
    AccountingPeriodIndexResource,
    AccountingPeriodOneOrManyRequest,
} from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { trans, transChoice } from 'laravel-vue-i18n';
import { ArchiveIcon, ArchiveRestoreIcon, CirclePlusIcon, EyeIcon, PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { ref } from 'vue';

defineOptions({
    layout: useLayout(TeamsFormLayout, () => ({})),
});

const props = defineProps<AccountingPeriodIndexProps>();

const alert = useAlert();

const selectedRows = ref<AccountingPeriodIndexResource[]>([]);
const rowsActions: DataTableRowsAction<AccountingPeriodIndexResource>[] = [
    {
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (items) => items.some((accountingPeriod) => !accountingPeriod.can_trash),
        callback: (items) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.accounting_periods.trash.confirm', items.length),
                callback: () =>
                    router.delete<AccountingPeriodOneOrManyRequest>(
                        route('teams.accounting-periods.trash', { team: props.team }),
                        {
                            data: { ids: items.map(({ id }) => id) },
                            only: ['accountingPeriods'],
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
        disabled: (items) => items.some((accountingPeriod) => !accountingPeriod.can_restore),
        callback: (items) =>
            alert({
                variant: 'success',
                description: transChoice('messages.accounting_periods.restore.confirm', items.length),
                callback: () =>
                    router.patch<AccountingPeriodOneOrManyRequest>(
                        route('teams.accounting-periods.restore', { team: props.team }),
                        { ids: items.map(({ id }) => id) },
                        {
                            only: ['accountingPeriods'],
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
        disabled: (items) => items.some((accountingPeriod) => !accountingPeriod.can_delete),
        callback: (items) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.accounting_periods.delete.confirm', items.length),
                callback: () =>
                    router.delete<AccountingPeriodOneOrManyRequest>(
                        route('teams.accounting-periods.delete', { team: props.team }),
                        {
                            data: { ids: items.map(({ id }) => id) },
                            only: ['accountingPeriods'],
                            onSuccess: () => {
                                selectedRows.value = [];
                            },
                        },
                    ),
            }),
    },
];
const rowActions: DataTableRowAction<AccountingPeriodIndexResource>[] = [
    {
        type: 'href',
        label: trans('view'),
        icon: EyeIcon,
        hidden: (accountingPeriod) => accountingPeriod.can_update,
        disabled: (accountingPeriod) => !accountingPeriod.can_view,
        href: (accountingPeriod) => route('teams.accounting-periods.edit', { team: props.team, accountingPeriod }),
    },
    {
        type: 'href',
        label: trans('edit'),
        icon: PencilIcon,
        disabled: (accountingPeriod) => !accountingPeriod.can_update,
        href: (accountingPeriod) => route('teams.accounting-periods.edit', { team: props.team, accountingPeriod }),
    },
    {
        type: 'callback',
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (accountingPeriod) => !accountingPeriod.can_trash,
        callback: (accountingPeriod) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.accounting_periods.trash.confirm', 1),
                callback: () =>
                    router.delete<AccountingPeriodOneOrManyRequest>(
                        route('teams.accounting-periods.trash', { team: props.team, accountingPeriod }),
                        {
                            only: ['accountingPeriods'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (accountingPeriod) => !accountingPeriod.can_restore,
        callback: (accountingPeriod) =>
            alert({
                variant: 'success',
                description: transChoice('messages.accounting_periods.restore.confirm', 1),
                callback: () =>
                    router.patch<AccountingPeriodOneOrManyRequest>(
                        route('teams.accounting-periods.restore', { team: props.team, accountingPeriod }),
                        undefined,
                        {
                            only: ['accountingPeriods'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('delete'),
        icon: Trash2Icon,
        disabled: (accountingPeriod) => !accountingPeriod.can_delete,
        callback: (accountingPeriod) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.accounting_periods.delete.confirm', 1),
                callback: () =>
                    router.delete<AccountingPeriodOneOrManyRequest>(
                        route('teams.accounting-periods.delete', { team: props.team, accountingPeriod }),
                        {
                            only: ['accountingPeriods'],
                        },
                    ),
            }),
    },
];

const filters = useFilters<AccountingPeriodIndexRequest>(
    route('teams.accounting-periods.index', { team: props.team }),
    {
        q: props.request.q ?? '',
        page: props.request.page ?? props.accountingPeriods?.meta.current_page,
        per_page: props.request.per_page ?? props.accountingPeriods?.meta.per_page,
        sort_by: props.request.sort_by,
        sort_direction: props.request.sort_direction,
        trashed: props.request.trashed,
    },
    {
        only: ['accountingPeriods'],
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

const format = useFormatter();
</script>

<template>
    <Head :title="$t('pages.teams.accounting_periods.index.title')" />

    <Section class="w-full">
        <SectionContent class="px-0!">
            <DataTable
                v-slot="{ rows }"
                v-model:selected-rows="selectedRows"
                v-model:sort-by="filters.sort_by"
                v-model:sort-direction="filters.sort_direction"
                :data="accountingPeriods"
                :rows-actions
                :row-actions
            >
                <FormContent class="flex items-center">
                    <TextInput v-model="filters.q" type="search" />
                    <FiltersSheet
                        :filters
                        :omit="['q', 'page', 'per_page', 'sort_by', 'sort_direction']"
                        :data="['trashed_filters']"
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
                                    <EnumCombobox v-model="filters.trashed" data="trashed_filters" />
                                </FormControl>
                            </FormField>
                        </FiltersSheetContent>
                    </FiltersSheet>
                </FormContent>
                <FormContent class="flex items-center justify-between">
                    <DataTableRowsActions />
                    <Button as-child>
                        <InertiaLink :href="route('teams.accounting-periods.create', { team })">
                            <CirclePlusIcon />
                            <CapitalizeText class="max-sm:hidden">
                                {{ $t('pages.teams.accounting_periods.create.title') }}
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
                                {{ $t('models.accounting_period.fields.label') }}
                            </DataTableHead>
                            <DataTableSortableHead value="starts_at">
                                {{ $t('models.accounting_period.fields.starts_at') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="ends_at">
                                {{ $t('models.accounting_period.fields.ends_at') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead v-if="filters.trashed" value="deleted_at">
                                {{ $t('models.accounting_period.fields.deleted_at') }}
                            </DataTableSortableHead>
                            <DataTableHead>
                                <DataTableHeadActions />
                            </DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow
                            v-for="accountingPeriod in rows"
                            :key="accountingPeriod.id"
                            :item="accountingPeriod"
                            :class="{ 'bg-destructive/5': accountingPeriod.deleted_at }"
                        >
                            <DataTableCell>
                                <DataTableRowCheckbox />
                            </DataTableCell>
                            <DataTableCell>
                                {{ accountingPeriod.label }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ format.date(accountingPeriod.starts_at) }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ format.date(accountingPeriod.ends_at) }}
                            </DataTableCell>
                            <DataTableCell v-if="filters.trashed">
                                <Badge v-if="accountingPeriod.deleted_at" variant="destructive">
                                    {{ format.date(accountingPeriod.deleted_at) }}
                                </Badge>
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
