<script setup lang="ts">
import ExpenseManagementCells from '@/components/expense/manangement/ExpenseManagementCells.vue';
import ExpenseManagementTypeRow from '@/components/expense/manangement/ExpenseManagementTypeRow.vue';
import {
    DataTable,
    DataTableBody,
    DataTableCell,
    DataTableContent,
    DataTableHead,
    DataTableHeader,
    DataTableRow,
} from '@/components/ui/custom/data-table';
import { Section, SectionContent } from '@/components/ui/custom/section';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { useFormatter, useLayout } from '@/composables';
import { AppLayout } from '@/layouts';
import {
    ExpenseManagementCellResource,
    ExpenseManagementIndexProps,
    ExpenseManagementTypeResource,
    ExpenseType,
} from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.expenses.management.index.title'),
                href: route('expenses.management.index'),
            },
        ],
    })),
});

const props = defineProps<ExpenseManagementIndexProps>();

const format = useFormatter();

const months = computed(() => props.request.accounting_period.months ?? []);

const typeRows = computed(
    (): Record<ExpenseType, ExpenseManagementTypeResource | undefined> => ({
        general: props.generalRow,
        employee: props.employeeRow,
        contractor: props.contractorRow,
    }),
);
const totalCells = computed((): ExpenseManagementCellResource[] =>
    months.value.map((_, index) => ({
        budget: Object.values(typeRows.value).reduce((total, row) => total + (row?.cells[index]?.budget ?? 0), 0),
        charge: Object.values(typeRows.value).reduce((total, row) => total + (row?.cells[index]?.charge ?? 0), 0),
    })),
);
const types = computed(() => Object.keys(typeRows.value) as ExpenseType[]);
const accountingPeriod = computed(() => props.request.accounting_period);
</script>

<template>
    <Head :title="$t('pages.expenses.management.index.title')" />

    <Section class="container-full">
        <SectionContent>
            <DataTable :data="types">
                <DataTableContent tab="table">
                    <DataTableHeader>
                        <DataTableRow>
                            <DataTableHead> </DataTableHead>
                            <DataTableHead v-for="month in months" :key="month" colspan="2">
                                {{ format.date(month, { month: 'long' }) }}
                                <span class="text-muted-foreground text-[0.6rem]">
                                    {{ format.date(month, { year: 'numeric' }) }}
                                </span>
                            </DataTableHead>
                            <DataTableHead colspan="3">
                                {{ accountingPeriod.label }}
                            </DataTableHead>
                        </DataTableRow>
                        <DataTableRow>
                            <DataTableHead> </DataTableHead>
                            <template v-for="month in months" :key="month">
                                <DataTableHead>
                                    <CapitalizeText> </CapitalizeText>
                                </DataTableHead>
                                <DataTableHead>
                                    <CapitalizeText>
                                        {{ $t('pages.expenses.management.index.real') }}
                                    </CapitalizeText>
                                </DataTableHead>
                            </template>
                            <DataTableHead> </DataTableHead>
                            <DataTableHead>
                                <CapitalizeText>
                                    {{ $t('pages.expenses.management.index.real') }}
                                </CapitalizeText>
                            </DataTableHead>
                            <DataTableHead>
                                {{ $t('pages.expenses.management.index.delta') }}
                            </DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow class="total">
                            <DataTableCell>
                                <CapitalizeText>
                                    {{ $t('total') }}
                                </CapitalizeText>
                            </DataTableCell>
                            <ExpenseManagementCells :accounting-period :cells="totalCells" />
                        </DataTableRow>
                        <ExpenseManagementTypeRow
                            v-for="type in types"
                            :key="type"
                            :type="type"
                            :cells="typeRows[type]?.cells ?? []"
                            :accounting-period
                        />
                    </DataTableBody>
                </DataTableContent>
            </DataTable>
        </SectionContent>
    </Section>
</template>

<style scoped>
@reference '@css';

:deep([data-slot='table-head']) {
    @apply bg-background border-x first:border-l-0 last:border-r-0 first:sm:left-0;
    @apply sticky top-0 z-10 text-center first:z-20;
}
:deep([data-slot='table-cell']) {
    @apply first:bg-background border-x first:border-l-0 last:border-r-0 first:sm:left-0;
    @apply min-w-32 text-right first:sticky first:z-10;
}
.total :deep([data-slot='table-cell']) {
    @apply font-semibold;
}
</style>
