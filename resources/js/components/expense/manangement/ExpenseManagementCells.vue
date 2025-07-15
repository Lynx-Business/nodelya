<script setup lang="ts">
import { DataTableCell } from '@/components/ui/custom/data-table';
import { PriceInput } from '@/components/ui/custom/input';
import { useFormatter } from '@/composables';
import { cn } from '@/lib/utils';
import { AccountingPeriodResource, ExpenseManagementCellResource } from '@/types';
import { whenever } from '@vueuse/core';
import { computed, HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    accountingPeriod: AccountingPeriodResource;
    cells: ExpenseManagementCellResource[];
    disabled?: boolean;
    class?: HTMLAttributes['class'];
};
const props = defineProps<Props>();

const months = computed(() => props.accountingPeriod.months ?? []);
whenever(
    () => months.value.length !== props.cells.length,
    () => {
        throw new Error('The number of months must match the number of cells.');
    },
    {
        immediate: true,
    },
);

const totalBudget = computed(() => props.cells.reduce((sum, cell) => sum + cell.budget, 0));
const totalCharge = computed(() => props.cells.reduce((sum, cell) => sum + cell.charge, 0));
const totalDifference = computed(() => totalCharge.value - totalBudget.value);

const format = useFormatter();

function cellClass(budget: number, charge: number): string {
    if (charge <= budget) {
        return 'bg-success/10';
    } else if (charge > budget) {
        return 'bg-destructive/10';
    }
    return '';
}
</script>

<template>
    <template v-for="(month, index) in months" :key="month">
        <DataTableCell :class="cn('border-x text-right text-sm', props.class)">
            {{ format.price(cells[index].budget) }}
        </DataTableCell>
        <DataTableCell
            v-if="!disabled && cells[index].can_update"
            :class="cn('p-0.5', cellClass(cells[index].budget, cells[index].charge), props.class)"
        >
            <PriceInput v-model="cells[index].charge" class="rounded-none border-none pr-6 text-right shadow-none" />
        </DataTableCell>
        <DataTableCell
            v-else
            :class="cn('text-right', cellClass(cells[index].budget, cells[index].charge), props.class)"
        >
            {{ format.price(cells[index].charge) }}
        </DataTableCell>
    </template>
    <DataTableCell :class="props.class">
        {{ format.price(totalBudget) }}
    </DataTableCell>
    <DataTableCell :class="cn(cellClass(totalBudget, totalCharge), props.class)">
        {{ format.price(totalCharge) }}
    </DataTableCell>
    <DataTableCell
        :class="
            cn(
                { 'before:content-[-]': totalDifference < 0, 'before:content-[+]': totalDifference > 0 },
                cellClass(totalBudget, totalCharge),
                props.class,
            )
        "
    >
        {{ format.price(Math.abs(totalDifference)) }}
    </DataTableCell>
</template>
