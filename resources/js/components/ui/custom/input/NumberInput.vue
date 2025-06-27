<script setup lang="ts">
import {
    NumberField,
    NumberFieldContent,
    NumberFieldDecrement,
    NumberFieldIncrement,
    NumberFieldInput,
} from '@/components/ui/number-field';
import { NumberFieldRootEmits, NumberFieldRootProps, useForwardPropsEmits } from 'reka-ui';

type Props = NumberFieldRootProps;
const props = defineProps<Props>();
const emits = defineEmits<NumberFieldRootEmits>();

const forwarded = useForwardPropsEmits(props, emits);
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
            <NumberFieldInput />
            <NumberFieldIncrement
                class="text-muted-foreground pointer-events-none text-xs !opacity-100"
                v-if="$slots.end"
            >
                <slot name="end" />
            </NumberFieldIncrement>
        </NumberFieldContent>
    </NumberField>
</template>
