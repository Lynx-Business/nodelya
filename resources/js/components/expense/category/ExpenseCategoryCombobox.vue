<script setup lang="ts">
import { InertiaCombobox, InertiaComboboxEmits, InertiaComboboxProps } from '@/components/ui/custom/combobox';
import { ExpenseCategoryListResource } from '@/types';
import { useForwardPropsEmits } from 'reka-ui';

type T = ExpenseCategoryListResource;
type Props = Partial<InertiaComboboxProps<T>>;
const props = withDefaults(defineProps<Props>(), {
    by: 'id',
    label: 'name',
    data: 'expenseCategories',
});
const emits = defineEmits<InertiaComboboxEmits<T>>();
const forwarded = useForwardPropsEmits(props, emits);
</script>

<template>
    <InertiaCombobox v-bind="forwarded" />
</template>
