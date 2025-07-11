<script setup lang="ts">
import { DataTableCell, DataTableRow } from '@/components/ui/custom/data-table';
import { LoadingIcon } from '@/components/ui/custom/loading';
import { useAuth } from '@/composables';
import { AccountingPeriodResource, ExpenseManagementBudgetResource } from '@/types';
import { useAxios } from '@vueuse/integrations/useAxios.mjs';
import ExpenseManagementCells from './ExpenseManagementCells.vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    accountingPeriod: AccountingPeriodResource;
    modelType?: string;
    modelId?: number;
};
const props = defineProps<Props>();

const { data, isLoading } = useAxios<ExpenseManagementBudgetResource[]>(
    route('api.expenses.management.budgets.index'),
    {
        params: {
            accounting_period_id: props.accountingPeriod.id,
            model_type: props.modelType,
            model_id: props.modelId,
        },
    },
);
const { abilities } = useAuth();
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
        <DataTableRow v-for="budget in data" :key="budget.expense_item.id">
            <DataTableCell class="bg-background sticky left-0 z-10 border-r pl-8 text-xs">
                {{ budget.expense_item.name }}
            </DataTableCell>
            <ExpenseManagementCells
                :accounting-period
                :cells="budget.cells"
                :disabled="!abilities.expenses.charges.create"
            />
        </DataTableRow>
    </template>
</template>
