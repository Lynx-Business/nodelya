<script setup lang="ts">
import { InertiaCombobox, InertiaComboboxEmits, InertiaComboboxProps } from '@/components/ui/custom/combobox';
import { ExpenseItemResource } from '@/types';
import { reactiveOmit } from '@vueuse/core';
import { useForwardPropsEmits } from 'reka-ui';
import { computed } from 'vue';

type T = ExpenseItemResource;
type Props = Partial<InertiaComboboxProps<T>> & {
    expenseCategoryId?: number;
};
const props = withDefaults(defineProps<Props>(), {
    by: 'id',
    label: 'name',
    data: 'expenseItems',
    group: true,
    groupBy: () => (option: T) => option.expense_category?.name ?? '',
});
const emits = defineEmits<InertiaComboboxEmits<T>>();
const delegatedProps = reactiveOmit(props, 'group', 'filter', 'expenseCategoryId');
const forwarded = useForwardPropsEmits(delegatedProps, emits);

const group = computed((): Props['group'] => props.expenseCategoryId == undefined);
const filter = computed((): Props['filter'] => {
    if (!props.expenseCategoryId) {
        return undefined;
    }

    return (item) => item.expense_category?.id === props.expenseCategoryId;
});
</script>

<template>
    <InertiaCombobox v-bind="forwarded" :group :filter />
</template>
