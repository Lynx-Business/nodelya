<script setup lang="ts">
import { cn } from '@/lib/utils';
import { reactiveOmit } from '@vueuse/core';
import { useForwardPropsEmits } from 'reka-ui';
import NumberInput, { NumberInputEmits, NumberInputProps } from './NumberInput.vue';

const props = withDefaults(defineProps<NumberInputProps>(), {
    step: () => 0.01,
});
const emits = defineEmits<NumberInputEmits>();
const delegatedProps = reactiveOmit(props, 'class');
const forwarded = useForwardPropsEmits(delegatedProps, emits);
</script>

<template>
    <NumberInput v-bind="forwarded" :class="cn('', props.class)">
        <template #end> € </template>
    </NumberInput>
</template>
