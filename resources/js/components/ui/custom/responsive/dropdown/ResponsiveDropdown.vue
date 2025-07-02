<script setup lang="ts">
import { Collapsible } from '@/components/ui/collapsible';
import { DropdownMenu } from '@/components/ui/dropdown-menu';
import { useIsDesktop } from '@/composables';
import { ref } from 'vue';

const isDesktop = useIsDesktop();

const isOpen = ref(false);
</script>

<template>
    <Collapsible v-if="isDesktop" v-model:open="isOpen">
        <slot />
    </Collapsible>
    <DropdownMenu v-else v-model:open="isOpen">
        <slot />
    </DropdownMenu>
</template>
