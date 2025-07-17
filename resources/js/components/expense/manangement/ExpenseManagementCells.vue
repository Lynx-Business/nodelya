<script setup lang="ts">
import { DataTableCell } from '@/components/ui/custom/data-table';
import { PriceInput } from '@/components/ui/custom/input';
import { useFormatter } from '@/composables';
import { cn } from '@/lib/utils';
import { AccountingPeriodResource, ExpenseManagementCellResource } from '@/types';
import { whenever } from '@vueuse/core';
import { computed, HTMLAttributes, ref, watch } from 'vue';

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

type Emits = {
    update: [index: number, value: number];
};
const emit = defineEmits<Emits>();

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

const charges = ref<(number | undefined)[]>([]);
watch(
    totalCharge,
    () => {
        charges.value = props.cells.map(({ charge }) => charge);
    },
    { immediate: true },
);

function cellClass(budget: number, charge: number): string {
    return charge <= budget ? 'bg-success/10' : 'bg-destructive/10';
}

function onBlur(index: number) {
    const charge = charges.value[index];
    if (charge === undefined) {
        charges.value[index] = props.cells[index].charge;
    } else if (charge !== props.cells[index].charge) {
        emit('update', index, charge);
    }
}

const format = useFormatter();
</script>

<template>
    <template v-for="(month, index) in months" :key="month">
        <DataTableCell :class="cn(props.class)">
            {{ format.price(cells[index].budget) }}
        </DataTableCell>
        <DataTableCell
            v-if="!disabled && cells[index].can_update"
            :ke="index"
            :class="cn('p-0.5', cellClass(cells[index].budget, cells[index].charge), props.class)"
        >
            <PriceInput
                class="rounded-none border-none pr-6 text-right shadow-none"
                v-model="charges[index]"
                :min="0"
                @blur="onBlur(index)"
            />
        </DataTableCell>
        <DataTableCell v-else :class="cn(cellClass(cells[index].budget, cells[index].charge), props.class)">
            {{ format.price(cells[index].charge) }}
        </DataTableCell>
    </template>
    <DataTableCell :class="cn(props.class)">
        {{ format.price(totalBudget) }}
    </DataTableCell>
    <DataTableCell :class="cn(cellClass(totalBudget, totalCharge), props.class)">
        {{ format.price(totalCharge) }}
    </DataTableCell>
    <DataTableCell
        :class="
            cn(
                { [`before:content-['-']`]: totalDifference < 0, [`before:content-['+']`]: totalDifference > 0 },
                cellClass(totalBudget, totalCharge),
                props.class,
            )
        "
    >
        {{ format.price(Math.abs(totalDifference)) }}
    </DataTableCell>
</template>
