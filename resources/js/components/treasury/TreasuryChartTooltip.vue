<script setup lang="ts">
import { ChartTooltip, ChartTooltipProps } from '@/components/ui/chart';
import { useFormatter } from '@/composables';
import { computed } from 'vue';

const props = defineProps<ChartTooltipProps>();
const format = useFormatter();
const data = computed(() => props.data.map((item) => ({ ...item, value: format.price(item.value) })));
const title = computed(() =>
    props.title
        ? format.date(new Date(Number(props.title)).toString(), { month: 'short', year: '2-digit' })
        : undefined,
);
</script>

<template>
    <ChartTooltip :title :data />
</template>
