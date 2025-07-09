<script setup lang="ts">
import { InertiaCombobox, InertiaComboboxEmits, InertiaComboboxProps } from '@/components/ui/custom/combobox';
import { ContractorResource } from '@/types';
import { useForwardPropsEmits } from 'reka-ui';

type T = ContractorResource;
type Props = Partial<InertiaComboboxProps<T>>;
const props = withDefaults(defineProps<Props>(), {
    by: 'id',
    label: 'full_name',
    data: 'contractors',
});
const emits = defineEmits<InertiaComboboxEmits<T>>();
const forwarded = useForwardPropsEmits(props, emits);
</script>

<template>
    <InertiaCombobox v-bind="forwarded" />
</template>
