<script setup lang="ts">
import AppBanner from '@/components/app/AppBanner.vue';
import AppShell from '@/components/app/AppShell.vue';
import AppSidebar from '@/components/app/sidebar/AppSidebar.vue';
import { Breadcrumbs } from '@/components/ui/custom/breadcrumbs';
import { SidebarInset, SidebarProvider, SidebarTrigger } from '@/components/ui/sidebar';
import { useAuth, useRouterComputed } from '@/composables';
import type { BreadcrumbItemType, NavItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

import {
    BanknoteIcon,
    ChartLineIcon,
    Columns3CogIcon,
    LayoutGridIcon,
    MonitorCogIcon,
    ReceiptEuroIcon,
    ShoppingBagIcon,
    UsersIcon,
} from 'lucide-vue-next';

type Props = {
    breadcrumbs?: BreadcrumbItemType[];
};
const { breadcrumbs = [] } = defineProps<Props>();

const sidebarOpen = usePage().props.sidebarOpen;

const { user } = useAuth();

const items = useRouterComputed((): NavItem[] => [
    {
        title: trans('layouts.app.sidebar.items.admin'),
        href: route('admin.index'),
        icon: MonitorCogIcon,
        hidden: !user.is_admin,
    },
    {
        title: trans('layouts.app.sidebar.items.index'),
        href: route('index'),
        icon: LayoutGridIcon,
        isActive: route().current('index'),
    },
    {
        title: trans('layouts.app.sidebar.items.expenses.management'),
        href: route('expenses.management.index'),
        icon: Columns3CogIcon,
        hidden: !user.permissions?.includes('expenses'),
        isActive: route().current('expenses.management.index'),
    },
    {
        title: trans('layouts.app.sidebar.items.expenses'),
        icon: BanknoteIcon,
        hidden: !user.permissions?.includes('expenses'),
        items: [
            {
                title: trans(`enums.expense.type.general`),
                href: route('expenses.budgets.index'),
                isActive: route().current('expenses.budgets.*') || route().current('expenses.charges.*'),
            },
            {
                title: trans(`enums.expense.type.employee`),
                href: route('employees.index'),
                isActive: route().current('employees.*'),
            },
            {
                title: trans(`enums.expense.type.contractor`),
                href: route('contractors.index'),
                isActive: route().current('contractors.*'),
            },
        ],
    },
    {
        title: trans('layouts.app.sidebar.items.client'),
        href: route('clients.index'),
        icon: UsersIcon,
        hidden: !user.permissions?.includes('deal'),
        isActive: route().current('clients.*'),
    },
    {
        title: trans('layouts.app.sidebar.items.commercial_deals'),
        href: route('deals.commercials.index'),
        icon: ShoppingBagIcon,
        hidden: !user.permissions?.includes('deal'),
        isActive: route().current('deals.commercials.*'),
    },
    {
        title: trans('layouts.app.sidebar.items.billing_deals'),
        href: route('deals.billings.index'),
        icon: ReceiptEuroIcon,
        isActive: route().current('deals.billings.*'),
    },
    {
        title: trans('layouts.app.sidebar.items.treasury'),
        href: route('treasury.index'),
        icon: ChartLineIcon,
        hidden: !user.permissions?.includes('treasury'),
        isActive: route().current('treasury.*'),
    },
]);
</script>

<template>
    <SidebarProvider :default-open="sidebarOpen">
        <AppSidebar :items />
        <SidebarInset>
            <AppBanner class="border-b" />
            <header
                class="border-sidebar-border/70 bg-background sticky inset-x-0 top-0 z-40 flex h-16 shrink-0 items-center gap-2 border-b px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:rounded-t-xl md:px-4"
            >
                <div class="flex items-center gap-2">
                    <SidebarTrigger class="-ml-1" />
                    <Breadcrumbs v-if="breadcrumbs.length > 0" :breadcrumbs />
                </div>
            </header>
            <AppShell>
                <slot />
            </AppShell>
        </SidebarInset>
    </SidebarProvider>
</template>
