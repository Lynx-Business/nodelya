<script setup lang="ts">
import ExpenseManagementTypeRow from '@/components/expense/manangement/ExpenseManagementTypeRow.vue';
import {
    DataTable,
    DataTableBody,
    DataTableContent,
    DataTableHead,
    DataTableHeader,
    DataTableRow,
} from '@/components/ui/custom/data-table';
import { Section, SectionContent } from '@/components/ui/custom/section';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { useFormatter, useLayout } from '@/composables';
import { AppLayout } from '@/layouts';
import { ExpenseManagementIndexProps, ExpenseManagementTypeResource, ExpenseType } from '@/types';
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
                            <DataTableHead class="bg-background sticky top-0 left-0 z-20 border-r"> </DataTableHead>
                            <DataTableHead v-for="month in months" :key="month" colspan="2" class="text-center">
                                {{ format.date(month, { month: 'long' }) }}
                                <span class="text-muted-foreground text-[0.6rem]">
                                    {{ format.date(month, { year: 'numeric' }) }}
                                </span>
                            </DataTableHead>
                            <DataTableHead colspan="2" class="text-center">
                                {{ accountingPeriod.label }}
                            </DataTableHead>
                            <DataTableHead class="text-center">
                                {{ $t('difference') }}
                            </DataTableHead>
                        </DataTableRow>
                        <DataTableRow>
                            <DataTableHead class="bg-background sticky top-0 left-0 z-20 border-r"> </DataTableHead>
                            <template v-for="month in months" :key="month">
                                <DataTableHead class="bg-background sticky top-0 z-10 text-center">
                                    <CapitalizeText>
                                        {{ $t('budget') }}
                                    </CapitalizeText>
                                </DataTableHead>
                                <DataTableHead class="bg-background sticky top-0 z-10 text-center">
                                    <CapitalizeText>
                                        {{ $t('charge') }}
                                    </CapitalizeText>
                                </DataTableHead>
                            </template>
                            <DataTableHead class="bg-background sticky top-0 z-10 text-center">
                                <CapitalizeText>
                                    {{ $t('budget') }}
                                </CapitalizeText>
                            </DataTableHead>
                            <DataTableHead class="bg-background sticky top-0 z-10 text-center">
                                <CapitalizeText>
                                    {{ $t('charge') }}
                                </CapitalizeText>
                            </DataTableHead>
                            <DataTableHead> </DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <ExpenseManagementTypeRow
                            v-for="type in types"
                            :key="type"
                            :type="type"
                            :cells="typeRows[type]?.cells ?? []"
                            :accounting-period="accountingPeriod"
                        />
                    </DataTableBody>
                </DataTableContent>
            </DataTable>
        </SectionContent>
    </Section>
</template>
