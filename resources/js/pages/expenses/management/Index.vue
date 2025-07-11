<script setup lang="ts">
import ExpenseManagementBudgetRows from '@/components/expense/manangement/ExpenseManagementBudgetRows.vue';
import ExpenseManagementCells from '@/components/expense/manangement/ExpenseManagementCells.vue';
import ExpenseManagementModelRows from '@/components/expense/manangement/ExpenseManagementModelRows.vue';
import { Badge, BadgeVariants } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
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
import { ExpenseManagementIndexProps, ExpenseManagementTypeResource, ExpenseType } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { ChevronRightIcon } from 'lucide-vue-next';
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
function typeBadgeVariant(type: ExpenseType): BadgeVariants['variant'] {
    switch (type) {
        case 'employee':
            return 'secondary';
        case 'contractor':
            return 'destructive';
        default:
            return 'default';
    }
}
const accountingPeriod = computed(() => props.request.accounting_period);
</script>

<template>
    <Head :title="$t('pages.expenses.management.index.title')" />

    <Section>
        <SectionContent>
            <DataTable :data="types">
                <DataTableContent tab="table">
                    <DataTableHeader>
                        <DataTableRow>
                            <DataTableHead> </DataTableHead>
                            <DataTableHead v-for="month in months" :key="month" colspan="2" class="text-center">
                                {{ format.date(month, { month: 'long' }) }}
                                <span class="text-muted-foreground text-[0.6rem]">
                                    {{ format.date(month, { year: 'numeric' }) }}
                                </span>
                            </DataTableHead>
                        </DataTableRow>
                        <DataTableRow>
                            <DataTableHead> </DataTableHead>
                            <template v-for="month in months" :key="month">
                                <DataTableHead class="bg-background sticky top-0 z-20 text-center">
                                    <CapitalizeText>
                                        {{ $t('budget') }}
                                    </CapitalizeText>
                                </DataTableHead>
                                <DataTableHead class="bg-background sticky top-0 z-20 text-center">
                                    <CapitalizeText>
                                        {{ $t('charge') }}
                                    </CapitalizeText>
                                </DataTableHead>
                            </template>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <Collapsible v-for="type in types" :key="type" class="group/collapsible" as-child>
                            <DataTableRow>
                                <DataTableCell class="bg-background sticky left-0 z-10 border-r p-0.5">
                                    <CollapsibleTrigger as-child>
                                        <Button class="w-full" variant="ghost" size="lg">
                                            <Badge variant="secondary">
                                                <CapitalizeText>
                                                    {{ $t(`enums.expense.type.${type}`) }}
                                                </CapitalizeText>
                                            </Badge>
                                            <ChevronRightIcon
                                                class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                                            />
                                        </Button>
                                    </CollapsibleTrigger>
                                </DataTableCell>
                                <ExpenseManagementCells
                                    v-if="typeRows[type]"
                                    :accounting-period
                                    :cells="typeRows[type].cells"
                                    disabled
                                />
                            </DataTableRow>
                            <CollapsibleContent as-child>
                                <ExpenseManagementBudgetRows v-if="type === 'general'" :accounting-period />
                                <ExpenseManagementModelRows v-else :accounting-period :model-type="type" />
                            </CollapsibleContent>
                        </Collapsible>
                    </DataTableBody>
                </DataTableContent>
            </DataTable>
        </SectionContent>
    </Section>
</template>
