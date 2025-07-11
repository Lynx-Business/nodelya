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

const format = useFormatter();
</script>

<template>
    <template v-for="(month, index) in months" :key="month">
        <DataTableCell :class="cn('border-x text-right text-sm', props.class)">
            {{ format.price(cells[index].budget) }}
        </DataTableCell>
        <DataTableCell
            v-if="!disabled"
            :class="
                cn(
                    'p-0.5',
                    {
                        'bg-success/10': cells[index].charge < (cells[index].budget ?? 0),
                        'bg-destructive/10': cells[index].charge > (cells[index].budget ?? 0),
                    },
                    props.class,
                )
            "
        >
            <PriceInput v-model="cells[index].charge" class="rounded-none border-none pr-6 text-right shadow-none" />
        </DataTableCell>
        <DataTableCell
            v-else
            :class="
                cn(
                    'text-right',
                    {
                        'bg-success/10': cells[index].charge < (cells[index].budget ?? 0),
                        'bg-destructive/10': cells[index].charge > (cells[index].budget ?? 0),
                    },
                    props.class,
                )
            "
        >
            {{ format.price(cells[index].charge) }}
        </DataTableCell>
    </template>
</template>
