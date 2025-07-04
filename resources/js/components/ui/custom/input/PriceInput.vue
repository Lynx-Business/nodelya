<template>
    <NumberInput v-bind="forwarded">
        <template #end> € </template>
    </NumberInput>
</template>

<script setup lang="ts">
import { NumberFieldRootEmits, NumberFieldRootProps, useForwardPropsEmits } from 'reka-ui';
import NumberInput from './NumberInput.vue';

const props = withDefaults(defineProps<NumberFieldRootProps>(), {
    step: () => 0.01,
});
const emits = defineEmits<NumberFieldRootEmits>();
const forwarded = useForwardPropsEmits(props, emits);
</script>
