<script setup lang="ts">
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
    DataTableRowsCheckbox,
    DataTableSortableHead,
} from '@/components/ui/custom/data-table';
import { FiltersSheet, FiltersSheetContent, FiltersSheetTrigger } from '@/components/ui/custom/filters';
import { FormContent, FormControl, FormField, FormLabel } from '@/components/ui/custom/form';
import { TextInput } from '@/components/ui/custom/input';
import { Section, SectionContent } from '@/components/ui/custom/section';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { useAlert, useFilters, useFormatter, useLayout } from '@/composables';
import { AppLayout } from '@/layouts';
import { BillingDealIndexProps, BillingDealIndexRequest, BillingDealIndexResource } from '@/types';

import { Head, router } from '@inertiajs/vue3';
import { trans, transChoice } from 'laravel-vue-i18n';
import { ArchiveIcon, ArchiveRestoreIcon, EyeIcon, PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { ref } from 'vue';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.billing_deals.index.title'),
                href: route('index'),
            },
        ],
    })),
});

const props = defineProps<BillingDealIndexProps>();

const format = useFormatter();
const alert = useAlert();

const selectedRows = ref<BillingDealIndexResource[]>([]);

const rowsActions: DataTableRowsAction<BillingDealIndexResource>[] = [
    {
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (items) => items.some((deal) => !deal.can_trash),
        callback: (items) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.billing_deals.trash.confirm', items.length),
                callback: () =>
                    router.delete(route('billing.deals.trash'), {
                        data: { ids: items.map(({ id }) => id) },
                        only: ['billing_deals'],
                        onSuccess: () => {
                            selectedRows.value = [];
                        },
                    }),
            }),
    },
    {
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (items) => items.some((deal) => !deal.can_restore),
        callback: (items) =>
            alert({
                variant: 'success',
                description: transChoice('messages.billing_deals.restore.confirm', items.length),
                callback: () =>
                    router.patch(
                        route('billing.deals.restore'),
                        { ids: items.map(({ id }) => id) },
                        {
                            only: ['billing_deals'],
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
        disabled: (items) => items.some((deal) => !deal.can_delete),
        callback: (items) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.billing_deals.delete.confirm', items.length),
                callback: () =>
                    router.delete(route('billing.deals.delete'), {
                        data: { ids: items.map(({ id }) => id) },
                        only: ['billing_deals'],
                        onSuccess: () => {
                            selectedRows.value = [];
                        },
                    }),
            }),
    },
];

const rowActions: DataTableRowAction<BillingDealIndexResource>[] = [
    {
        type: 'href',
        label: trans('edit'),
        icon: PencilIcon,
        href: (deal) => route('billing.deals.edit', { deal }),
    },
    {
        type: 'href',
        label: trans('view'),
        icon: EyeIcon,
        href: (deal) => route('billing.deals.edit', { deal }),
    },
    {
        type: 'callback',
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (deal) => !deal.can_trash,
        callback: (deal) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.billing_deals.trash.confirm', 1),
                callback: () =>
                    router.delete(route('billing.deals.trash', { deal }), {
                        only: ['billing_deals'],
                    }),
            }),
    },
    {
        type: 'callback',
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (deal) => !deal.can_restore,
        callback: (deal) =>
            alert({
                variant: 'success',
                description: transChoice('messages.billing_deals.restore.confirm', 1),
                callback: () =>
                    router.patch(route('billing.deals.restore', { deal }), undefined, {
                        only: ['billing_deals'],
                    }),
            }),
    },
    {
        type: 'callback',
        label: trans('delete'),
        icon: Trash2Icon,
        disabled: (deal) => !deal.can_delete,
        callback: (deal) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.billing_deals.delete.confirm', 1),
                callback: () =>
                    router.delete(route('billing.deals.delete', { deal }), {
                        only: ['billing_deals'],
                    }),
            }),
    },
];

const filters = useFilters<BillingDealIndexRequest>(
    route('billing.deals.index'),
    {
        q: props.request.q ?? '',
        page: props.billing_deals?.meta.current_page,
        per_page: props.billing_deals?.meta.per_page,
        sort_by: props.request.sort_by,
        sort_direction: props.request.sort_direction,
        trashed: props.request.trashed,
    },
    {
        only: ['billing_deals'],
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
    <Head :title="trans('pages.billing_deals.title')" />

    <Section>
        <SectionContent>
            <DataTable
                v-slot="{ rows }"
                v-model:selected-rows="selectedRows"
                v-model:sort-by="filters.sort_by"
                v-model:sort-direction="filters.sort_direction"
                :data="billing_deals"
                :rows-actions
                :row-actions
            >
                <FormContent class="flex items-center">
                    <TextInput v-model="filters.q" type="search" />
                    <FiltersSheet
                        :filters="filters"
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

                <DataTableContent tab="table">
                    <DataTableHeader>
                        <DataTableRow>
                            <DataTableHead>
                                <DataTableRowsCheckbox />
                            </DataTableHead>
                            <DataTableSortableHead value="client">
                                {{ $t('models.billing_deals.fields.client_id') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="name">
                                {{ $t('models.billing_deals.fields.name') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="amount">
                                {{ $t('models.billing_deals.fields.amount') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="code">
                                {{ $t('models.billing_deals.fields.code') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="amount">
                                {{ $t('models.billing_deals.fields.total_sales') }}
                            </DataTableSortableHead>
                            <DataTableHead>
                                <DataTableHeadActions />
                            </DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow v-for="deal in rows" :key="deal.id" :item="deal">
                            <DataTableCell>
                                <DataTableRowCheckbox />
                            </DataTableCell>
                            <DataTableCell>
                                <CapitalizeText>
                                    {{ deal?.client?.name }}
                                </CapitalizeText>
                            </DataTableCell>
                            <DataTableCell>
                                <CapitalizeText>
                                    {{ deal.name }}
                                </CapitalizeText>
                            </DataTableCell>
                            <DataTableCell>
                                {{ format.price(deal.amount) }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ deal.code }}
                            </DataTableCell>
                            <DataTableCell class="bg-gray-300/30">
                                {{ format.price((deal.amount * deal.success_rate) / 100) }}
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
