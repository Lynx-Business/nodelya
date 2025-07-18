<script setup lang="ts">
import AccountingPeriodCombobox from '@/components/accounting-period/AccountingPeriodCombobox.vue';
import { Button } from '@/components/ui/button';
import {
    DataTable,
    DataTableBody,
    DataTableCell,
    DataTableContent,
    DataTableHead,
    DataTableHeadActions,
    DataTableHeader,
    DataTableRow,
} from '@/components/ui/custom/data-table';
import { FormContent } from '@/components/ui/custom/form';
import { TextInput } from '@/components/ui/custom/input';
import { InertiaLink } from '@/components/ui/custom/link';
import { Section, SectionContent } from '@/components/ui/custom/section';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { useAlert, useFilters, useFormatter, useLayout, useLocale } from '@/composables';
import { AppLayout } from '@/layouts';
import { FlowIndexProps, FlowIndexRequest, MonthlyExpenseData } from '@/types';

import { Head } from '@inertiajs/vue3';
import { reactiveOmit } from '@vueuse/core';
import { trans } from 'laravel-vue-i18n';
import { CirclePlusIcon } from 'lucide-vue-next';
import { computed } from 'vue';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.deals.billings.index.title'),
                href: route('deals.billings.index'),
            },
        ],
    })),
});

const props = defineProps<FlowIndexProps>();

const format = useFormatter();
const alert = useAlert();
const { locale } = useLocale();

const dynamicMonths = computed(() => {
    if (!props.accounting_period_months) return [];

    const months: { key: string; label: string }[] = [];
    props.accounting_period_months.forEach((month) => {
        months.push({
            key: month,
            label: formatMonth(month),
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

function findExpenseForMonth(
    expenses: Record<string, MonthlyExpenseData> | undefined | null,
    monthKey: string,
): MonthlyExpenseData | undefined {
    if (!expenses) return undefined;
    return expenses[monthKey];
}

const filters = useFilters<FlowIndexRequest>(
    route('flows.index'),
    {
        q: props.request.q ?? '',
        sort_by: props.request.sort_by,
        sort_direction: props.request.sort_direction,
        trashed: props.request.trashed,
        accounting_period: props.request.accounting_period,
    },
    {
        only: ['table_data'],
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
    <Head :title="trans('pages.deals.billings.title')" />

    <Section>
        <SectionContent>
            <DataTable v-slot="{ rows }" :data="table_data">
                <FormContent class="flex items-center sm:flex">
                    <TextInput v-model="filters.q" type="search" />
                    <AccountingPeriodCombobox v-model="filters.accounting_period" required />
                </FormContent>
                <FormContent class="flex items-center justify-between">
                    <Button as-child>
                        <InertiaLink :href="route('flows.create')">
                            <CirclePlusIcon />
                            <CapitalizeText>
                                {{ $t('pages.flows.create.title') }}
                            </CapitalizeText>
                        </InertiaLink>
                    </Button>
                </FormContent>

                <DataTableContent tab="table">
                    <DataTableHeader>
                        <DataTableRow>
                            <DataTableHead>
                                {{ $t('models.deal.billing.fields.name') }}
                            </DataTableHead>

                            <DataTableHead v-for="(month, index) in dynamicMonths" :key="index">
                                {{ month.label }}
                            </DataTableHead>
                            <DataTableHead>
                                <DataTableHeadActions />
                            </DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow v-for="(row, index) in rows" :key="index">
                            <DataTableCell>
                                <CapitalizeText>{{ row.name }}</CapitalizeText>
                            </DataTableCell>
                            <DataTableCell v-for="month in dynamicMonths" :key="month.key">
                                {{ format.price(row.values[month.key] ?? 0) }}
                            </DataTableCell>
                        </DataTableRow>
                    </DataTableBody>
                </DataTableContent>
            </DataTable>
        </SectionContent>
    </Section>
</template>
