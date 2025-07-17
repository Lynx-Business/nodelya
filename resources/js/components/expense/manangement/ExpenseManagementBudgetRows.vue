<script setup lang="ts">
import { DataTableCell, DataTableRow } from '@/components/ui/custom/data-table';
import { LoadingIcon } from '@/components/ui/custom/loading';
import { useFormatter } from '@/composables';
import {
    AccountingPeriodResource,
    ExpenseChargeFormRequest,
    ExpenseChargeResource,
    ExpenseManagementBudgetResource,
} from '@/types';
import { router } from '@inertiajs/vue3';
import { watchTriggerable } from '@vueuse/core';
import { useAxios } from '@vueuse/integrations/useAxios.mjs';
import ExpenseManagementCells from './ExpenseManagementCells.vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    accountingPeriod: AccountingPeriodResource;
    modelType?: 'employee' | 'contractor';
    modelId?: number;
};
const props = defineProps<Props>();

const budgets = defineModel<ExpenseManagementBudgetResource[]>('budgets', {
    default: () => [],
});

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
const dataWatch = watchTriggerable(data, () => {
    budgets.value = [...data.value];
});

const { execute: storeCharge } = useAxios<ExpenseChargeResource>();
const format = useFormatter();
function onUpdate(expenseBudget: ExpenseManagementBudgetResource, index: number, amount: number) {
    const formData: ExpenseChargeFormRequest = {
        amount,
        charged_at: format.timestamp((props.accountingPeriod.months ?? [])[index]),
        expense_item_id: expenseBudget.expense_item.id,
        model_type: props.modelType,
        model_id: props.modelId,
    };

    const expenseChargeId = expenseBudget.cells[index].charge_id;
    const url = !expenseChargeId
        ? route('api.expenses.management.charges.store')
        : route('api.expenses.management.charges.update', { expenseCharge: expenseChargeId });
    const method = !expenseChargeId ? 'POST' : 'PUT';

    storeCharge(url, { method, data: formData }).then((response) => {
        const charge = response.data.value;
        if (!charge) {
            return;
        }

        expenseBudget.cells = expenseBudget.cells.toSpliced(index, 1, {
            ...expenseBudget.cells[index],
            charge_id: charge.id,
            charge: charge.amount,
            can_update: charge.can_update,
        });
        dataWatch.trigger();

        switch (props.modelType) {
            case 'employee':
                router.reload({
                    only: ['employeeRow'],
                });
                break;
            case 'contractor':
                router.reload({
                    only: ['contractorRow'],
                });
                break;
            default:
                router.reload({
                    only: ['generalRow'],
                });
                break;
        }
    });
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
        <DataTableRow v-for="budget in budgets" :key="budget.expense_item.id">
            <DataTableCell class="pl-8 text-left! text-xs">
                {{ budget.expense_item.name }}
            </DataTableCell>
            <ExpenseManagementCells
                :accounting-period
                :cells="budget.cells"
                @update="(index, value) => onUpdate(budget, index, value)"
            />
        </DataTableRow>
    </template>
</template>
