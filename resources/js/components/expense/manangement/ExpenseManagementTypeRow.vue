<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { DataTableCell, DataTableRow } from '@/components/ui/custom/data-table';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { AccountingPeriodResource, ExpenseManagementCellResource, ExpenseType } from '@/types';
import { ChevronRightIcon } from 'lucide-vue-next';
import ExpenseManagementBudgetRows from './ExpenseManagementBudgetRows.vue';
import ExpenseManagementCells from './ExpenseManagementCells.vue';
import ExpenseManagementModelRows from './ExpenseManagementModelRows.vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    type: ExpenseType;
    cells: ExpenseManagementCellResource[];
    accountingPeriod: AccountingPeriodResource;
};
defineProps<Props>();
</script>

<template>
    <Collapsible class="group/collapsible" as-child>
        <DataTableRow>
            <DataTableCell class="p-0.5">
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
            <ExpenseManagementCells :accounting-period :cells disabled />
        </DataTableRow>
        <CollapsibleContent as-child>
            <ExpenseManagementBudgetRows v-if="type === 'general'" :accounting-period />
            <ExpenseManagementModelRows v-else :accounting-period :model-type="type" />
        </CollapsibleContent>
    </Collapsible>
</template>
