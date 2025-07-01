<script setup lang="ts">
import { CollapsibleTrigger } from '@/components/ui/collapsible';
import { DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useIsDesktop } from '@/composables';

const isDesktop = useIsDesktop();
</script>

<template>
    <CollapsibleTrigger v-if="isDesktop">
        <slot />
    </CollapsibleTrigger>
    <DropdownMenuTrigger v-else>
        <slot />
    </DropdownMenuTrigger>
</template>
