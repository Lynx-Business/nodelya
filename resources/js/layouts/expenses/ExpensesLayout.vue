<script setup lang="ts">
import { ResponsiveTabs, ResponsiveTabsTrigger } from '@/components/ui/custom/responsive-tabs';
import { Section, SectionContent } from '@/components/ui/custom/section';
import { Separator } from '@/components/ui/separator';
import { clearSessionFilters, useLayout, useRouterComputed } from '@/composables';
import { AppLayout } from '@/layouts';
import { NavItemHref } from '@/types';
import { trans } from 'laravel-vue-i18n';
import { BanknoteIcon } from 'lucide-vue-next';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.expenses.title'),
                href: route().current('expenses.budgets.*')
                    ? route('expenses.budgets.index')
                    : route('expenses.charges.index'),
            },
        ],
    })),
});

const sidebarNavItems = useRouterComputed((): NavItemHref[] =>
    [
        {
            title: trans('layouts.expenses.budgets'),
            href: route('expenses.budgets.index'),
            icon: BanknoteIcon,
            isActive: route().current('expenses.budgets.*'),
        },
        {
            title: trans('layouts.expenses.charges'),
            href: route('expenses.charges.index'),
            icon: BanknoteIcon,
            isActive: route().current('expenses.charges.*'),
        },
    ].map((item) =>
        Object.assign(item, {
            options: {
                onBefore: () => clearSessionFilters(item.href),
            },
        }),
    ),
);
</script>

<template>
    <Section>
        <SectionContent>
            <div class="flex flex-col space-y-8 lg:flex-row lg:space-y-0 lg:space-x-12">
                <aside class="min-w-48">
                    <ResponsiveTabs :tabs="sidebarNavItems" orientation="vertical" variant="ghost">
                        <ResponsiveTabsTrigger align="start" />
                    </ResponsiveTabs>
                </aside>

                <Separator class="md:hidden" />

                <div class="flex-1">
                    <section class="space-y-8 *:not-[.w-full]:max-w-xl">
                        <slot />
                    </section>
                </div>
            </div>
        </SectionContent>
    </Section>
</template>
