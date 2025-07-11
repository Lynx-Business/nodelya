<template>
    <NumberInput v-bind="forwarded" :class="cn('', props.class)">
        <template #end> € </template>
    </NumberInput>
</template>

<script setup lang="ts">
import { cn } from '@/lib/utils';
import { reactiveOmit } from '@vueuse/core';
import { NumberFieldRootEmits, NumberFieldRootProps, useForwardPropsEmits } from 'reka-ui';
import { HTMLAttributes } from 'vue';
import NumberInput from './NumberInput.vue';

type Props = NumberFieldRootProps & {
    class?: HTMLAttributes['class'];
};
const props = withDefaults(defineProps<Props>(), {
    step: () => 0.01,
});
const emits = defineEmits<NumberFieldRootEmits>();
const delegatedProps = reactiveOmit(props, 'class');
const forwarded = useForwardPropsEmits(delegatedProps, emits);
</script>
