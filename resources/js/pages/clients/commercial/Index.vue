<script setup lang="ts">
import AccountingPeriodCombobox from '@/components/accounting-period/AccountingPeriodCombobox.vue';
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
    DataTableRowsCheckbox,
    DataTableSortableHead,
} from '@/components/ui/custom/data-table';
import { FiltersSheet, FiltersSheetContent, FiltersSheetTrigger } from '@/components/ui/custom/filters';
import { FormContent, FormControl, FormField, FormLabel } from '@/components/ui/custom/form';
import { NumberInput, PriceInput, TextInput } from '@/components/ui/custom/input';
import { InertiaLink } from '@/components/ui/custom/link';
import { Section, SectionContent } from '@/components/ui/custom/section';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { useAlert, useAuth, useFilters, useFormatter, useLayout, useLocale } from '@/composables';
import ClientFormLayout from '@/layouts/client/ClientFormLayout.vue';
import { CommercialDealIndexProps, CommercialDealIndexRequest, DealResource, MonthlyExpenseData } from '@/types';

import { Head, router } from '@inertiajs/vue3';
import { reactiveOmit } from '@vueuse/core';
import { trans, transChoice } from 'laravel-vue-i18n';
import { ArchiveIcon, ArchiveRestoreIcon, CirclePlusIcon, EyeIcon, PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { computed, ref } from 'vue';

defineOptions({
    layout: useLayout(ClientFormLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.deals.commercials.index.title'),
                href: route('index'),
            },
        ],
    })),
});

const props = defineProps<CommercialDealIndexProps>();
console.log('props => ', props);
const { abilities } = useAuth();
const format = useFormatter();
const alert = useAlert();
const { locale } = useLocale();

const dynamicMonths = computed(() => {
    if (!props.accounting_period_months) return [];

    const months: { key: string; lettre: string }[] = [];
    props.accounting_period_months.forEach((month) => {
        months.push({
            key: month,
            lettre: formatMonth(month),
        });
    });

    return months;
});

function formatMonth(monthString: string): string {
    const [year, month] = monthString.split('-');
    const date = new Date(parseInt(year), parseInt(month) - 1, 1);
    return new Intl.DateTimeFormat(locale.value, {
        month: 'long',
        year: 'numeric',
    }).format(date);
}

function findExpenseForMonth(expenses: Record<string, MonthlyExpenseData> | undefined, monthKey: string) {
    if (!expenses) return undefined;
    return expenses[monthKey];
}

const selectedRows = ref<DealResource[]>([]);

const rowsActions: DataTableRowsAction<DealResource>[] = [
    {
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (items) => items.some((deal) => !deal.can_trash),
        callback: (items) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.deals.commercials.trash.confirm', items.length),
                callback: () =>
                    router.delete(route('clients.commercials.trash', { client: props.client! }), {
                        data: { ids: items.map(({ id }) => id) },
                        only: ['commercial_deals'],
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
                description: transChoice('messages.deals.commercials.restore.confirm', items.length),
                callback: () =>
                    router.patch(
                        route('clients.commercials.restore', { client: props.client! }),
                        { ids: items.map(({ id }) => id) },
                        {
                            only: ['commercial_deals'],
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
                description: transChoice('messages.deals.commercials.delete.confirm', items.length),
                callback: () =>
                    router.delete(route('clients.commercials.delete', { client: props.client! }), {
                        data: { ids: items.map(({ id }) => id) },
                        only: ['commercial_deals'],
                        onSuccess: () => {
                            selectedRows.value = [];
                        },
                    }),
            }),
    },
];

const rowActions: DataTableRowAction<DealResource>[] = [
    {
        type: 'href',
        label: trans('view'),
        hidden: (deal) => deal.can_update || false,
        disabled: (deal) => !deal.can_view,
        icon: EyeIcon,
        href: (deal) => route('clients.commercials.edit', { deal, client: props.client! }),
    },
    {
        type: 'href',
        label: trans('edit'),
        icon: PencilIcon,
        disabled: (deal) => !deal.can_update && !deal.can_restore,
        href: (deal) => route('clients.commercials.edit', { deal, client: props.client! }),
    },
    {
        type: 'callback',
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (deal) => !deal.can_trash,
        callback: (deal) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.deals.commercials.trash.confirm', 1),
                callback: () =>
                    router.delete(route('clients.commercials.trash', { deal, client: props.client! }), {
                        only: ['commercial_deals'],
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
                description: transChoice('messages.deals.commercials.restore.confirm', 1),
                callback: () =>
                    router.patch(route('clients.commercials.restore', { deal, client: props.client! }), undefined, {
                        only: ['commercial_deals'],
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
                description: transChoice('messages.deals.commercials.delete.confirm', 1),
                callback: () =>
                    router.delete(route('clients.commercials.delete', { deal, client: props.client! }), {
                        only: ['commercial_deals'],
                    }),
            }),
    },
];

const filters = useFilters<CommercialDealIndexRequest>(
    route('deals.commercials.index'),
    {
        q: props.request.q ?? '',
        name: props.request.name ?? '',
        amount: props.request.amount,
        success_rate: props.request.success_rate,
        code: props.request.name,
        page: props.commercial_deals?.meta.current_page,
        per_page: props.commercial_deals?.meta.per_page,
        sort_by: props.request.sort_by,
        sort_direction: props.request.sort_direction,
        trashed: props.request.trashed,
        accounting_period: props.request.accounting_period,
    },
    {
        only: ['commercial_deals'],
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
                ...reactiveOmit(data, 'accounting_period'),
                accounting_period_id: data.accounting_period?.id,
            };
        },
    },
);
</script>

<template>
    <Head :title="trans('pages.deals.commercials.title')" />

    <Section>
        <SectionContent>
            <DataTable
                v-slot="{ rows }"
                v-model:selected-rows="selectedRows"
                v-model:sort-by="filters.sort_by"
                v-model:sort-direction="filters.sort_direction"
                :data="commercial_deals"
                :rows-actions
                :row-actions
            >
                <FormContent class="flex items-center">
                    <TextInput v-model="filters.q" type="search" />
                    <AccountingPeriodCombobox v-model="filters.accounting_period" required />
                    <FiltersSheet
                        :filters="filters"
                        :omit="['q', 'page', 'per_page', 'sort_by', 'sort_direction', 'accounting_period_id']"
                        :data="['trashed_filters', 'clients']"
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
                            <FormField>
                                <FormField>
                                    <FormLabel>
                                        <CapitalizeText>
                                            {{ $t('models.deal.commercial.fields.name') }}
                                        </CapitalizeText>
                                    </FormLabel>
                                    <FormControl>
                                        <TextInput v-model="filters.name" />
                                    </FormControl>
                                </FormField>
                                <FormLabel>
                                    <CapitalizeText>
                                        {{ $t('models.deal.commercial.fields.amount') }}
                                    </CapitalizeText>
                                </FormLabel>
                                <FormControl>
                                    <PriceInput v-model="filters.amount" />
                                </FormControl>
                            </FormField>
                            <FormField>
                                <FormLabel>
                                    <CapitalizeText>
                                        {{ $t('models.deal.commercial.fields.success_rate') }}
                                    </CapitalizeText>
                                </FormLabel>
                                <FormControl>
                                    <NumberInput v-model="filters.success_rate" :min="0" :max="100" />
                                </FormControl>
                            </FormField>
                            <FormField>
                                <FormLabel>
                                    <CapitalizeText>
                                        {{ $t('models.deal.commercial.fields.code') }}
                                    </CapitalizeText>
                                </FormLabel>
                                <FormControl>
                                    <TextInput v-model="filters.code" />
                                </FormControl>
                            </FormField>
                        </FiltersSheetContent>
                    </FiltersSheet>
                </FormContent>
                <FormContent class="flex items-center justify-between" v-if="abilities.deals.create">
                    <Button as-child>
                        <InertiaLink :href="route('clients.commercials.create', { client: client! })">
                            <CirclePlusIcon />
                            <CapitalizeText>
                                {{ $t('pages.deals.commercials.create.title') }}
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
                            <DataTableSortableHead value="client">
                                {{ $t('models.deal.commercial.fields.client_id') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="name">
                                {{ $t('models.deal.commercial.fields.name') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="amount_in_cents">
                                {{ $t('models.deal.commercial.fields.amount') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="code">
                                {{ $t('models.deal.commercial.fields.code') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="success_rate">
                                {{ $t('models.deal.billing.fields.success_rate') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="ordered_at">
                                {{ $t('models.deal.commercial.fields.ordered_at') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="duration_in_months">
                                {{ $t('models.deal.commercial.fields.duration_in_months') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="amount_in_cents">
                                {{ $t('models.deal.commercial.fields.total_sales') }}
                            </DataTableSortableHead>

                            <DataTableHead v-for="(month, index) in dynamicMonths" :key="index">
                                {{ month.lettre }}
                            </DataTableHead>
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
                            <DataTableCell>
                                {{ deal.success_rate + ' %' }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ format.date(deal.ordered_at) }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ deal.duration_in_months }}
                            </DataTableCell>
                            <DataTableCell class="bg-gray-300/30">
                                {{ format.price((deal.amount * deal.success_rate) / 100) }}
                            </DataTableCell>

                            <DataTableCell v-for="(month, index) in dynamicMonths" :key="index" class="min-w-30">
                                {{ format.price(findExpenseForMonth(deal.monthly_expenses, month.key)?.amount ?? 0) }}
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
