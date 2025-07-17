<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { DataTableCell, DataTableRow } from '@/components/ui/custom/data-table';
import { LoadingIcon } from '@/components/ui/custom/loading';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { AccountingPeriodResource, ExpenseManagementBudgetResource, ExpenseManagementModelResource } from '@/types';
import { computedWithControl } from '@vueuse/core';
import { useAxios } from '@vueuse/integrations/useAxios.mjs';
import { ChevronRightIcon, ExternalLinkIcon } from 'lucide-vue-next';
import ExpenseManagementBudgetRows from './ExpenseManagementBudgetRows.vue';
import ExpenseManagementCells from './ExpenseManagementCells.vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    accountingPeriod: AccountingPeriodResource;
    modelType: 'employee' | 'contractor';
};
const props = defineProps<Props>();

const { data, isLoading } = useAxios<ExpenseManagementModelResource[]>(
    route(`api.expenses.management.${props.modelType}s.index`),
    {
        params: {
            accounting_period_id: props.accountingPeriod.id,
        },
    },
);
const models = computedWithControl(isLoading, () => data.value);

function onUpdateBudgets(index: number, budgets: ExpenseManagementBudgetResource[]) {
    data.value[index].cells = data.value[index].cells.map((cell, cellIndex) => ({
        ...cell,
        charge: budgets.reduce((total, budget) => total + budget.cells[cellIndex].charge, 0),
    }));
    models.trigger();
}
</script>

<template>
    <DataTableRow v-if="isLoading">
        <DataTableCell>
            <div class="grid place-items-center">
                <LoadingIcon variant="primary" />
            </div>
        </DataTableCell>
        <DataTableCell colspan="100"> </DataTableCell>
    </DataTableRow>
    <template v-else>
        <Collapsible class="group/collapsible" v-for="(model, index) in models" :key="model.model_id" as-child>
            <DataTableRow>
                <DataTableCell class="p-0.5">
                    <CollapsibleTrigger as-child>
                        <Button class="w-full" variant="ghost" size="lg">
                            <CapitalizeText>
                                {{ model.label }}
                            </CapitalizeText>
                            <Button class="ml-auto opacity-50 hover:opacity-100" size="icon" variant="link" as-child>
                                <a
                                    :href="route(`${props.modelType}s.edit`, model.model_id)"
                                    target="_blank"
                                    @click.stop
                                >
                                    <ExternalLinkIcon />
                                </a>
                            </Button>
                            <ChevronRightIcon
                                class="transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                            />
                        </Button>
                    </CollapsibleTrigger>
                </DataTableCell>
                <ExpenseManagementCells :accounting-period :cells="model.cells" disabled />
            </DataTableRow>
            <CollapsibleContent as-child>
                <ExpenseManagementBudgetRows
                    :accounting-period
                    :model-type
                    :model-id="model.model_id"
                    @update:budgets="onUpdateBudgets(index, $event)"
                />
            </CollapsibleContent>
        </Collapsible>
    </template>
</template>
