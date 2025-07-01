<script setup lang="ts">
import { CollapsibleContent } from '@/components/ui/collapsible';
import { DropdownMenuContent } from '@/components/ui/dropdown-menu';
import { useIsDesktop } from '@/composables';

const isDesktop = useIsDesktop();
</script>

<template>
    <CollapsibleContent v-if="isDesktop">
        <slot />
    </CollapsibleContent>
    <DropdownMenuContent v-else>
        <slot />
    </DropdownMenuContent>
</template>
