<script lang="ts">
export type NumberInputProps = NumberFieldRootProps & {
    class?: HTMLAttributes['class'];
};
export type NumberInputEmits = NumberFieldRootEmits & {
    blur: [];
    focus: [];
};
</script>

<script setup lang="ts">
import {
    NumberField,
    NumberFieldContent,
    NumberFieldDecrement,
    NumberFieldIncrement,
    NumberFieldInput,
} from '@/components/ui/number-field';
import { reactiveOmit } from '@vueuse/core';
import { NumberFieldRootEmits, NumberFieldRootProps, useForwardPropsEmits } from 'reka-ui';
import { HTMLAttributes } from 'vue';

const props = defineProps<NumberInputProps>();

const emits = defineEmits<NumberInputEmits>();

const delegatedProps = reactiveOmit(props, 'class');
const forwarded = useForwardPropsEmits(delegatedProps, emits);
</script>

<template>
    <NumberField v-bind="forwarded">
        <NumberFieldContent>
            <NumberFieldDecrement
                class="text-muted-foreground pointer-events-none text-xs !opacity-100"
                v-if="$slots.start"
            >
                <slot name="start" />
            </NumberFieldDecrement>
            <NumberFieldInput :class="props.class" @focus="$emit('focus')" @blur="$emit('blur')" />
            <NumberFieldIncrement
                class="text-muted-foreground pointer-events-none text-xs !opacity-100"
                v-if="$slots.end"
            >
                <slot name="end" />
            </NumberFieldIncrement>
        </NumberFieldContent>
    </NumberField>
</template>
