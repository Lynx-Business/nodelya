<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <div data-slot="card-footer" :class="cn('flex items-center gap-2 px-6 [.border-t]:pt-6', props.class)">
        <slot />
    </div>
</template>
