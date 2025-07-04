<script setup lang="ts">
import { ResponsiveTabs, ResponsiveTabsTrigger } from '@/components/ui/custom/responsive-tabs';
import { Section, SectionContent } from '@/components/ui/custom/section';
import { Separator } from '@/components/ui/separator';
import { clearSessionFilters, useLayout, useRouterComputed } from '@/composables';
import { AppLayout } from '@/layouts';
import { NavItemHref } from '@/types';
import { trans } from 'laravel-vue-i18n';
import { BanknoteIcon, SquarePenIcon, WalletIcon } from 'lucide-vue-next';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.contractors.index.title'),
                href: route('contractors.index'),
            },
            {
                title: trans('pages.contractors.edit.title'),
                href: route('contractors.edit', {
                    contractor: route().params.contractor,
                }),
            },
        ],
    })),
});

const sidebarNavItems = useRouterComputed((): NavItemHref[] =>
    [
        {
            title: trans('layouts.contractors.form.edit'),
            href: route('contractors.edit', {
                contractor: route().params.contractor,
            }),
            icon: SquarePenIcon,
            isActive: route().current('contractors.edit'),
        },
        {
            title: trans('layouts.contractors.form.budgets'),
            href: route('contractors.expenses.budgets.index', {
                contractor: route().params.contractor,
            }),
            icon: BanknoteIcon,
            isActive: route().current('contractors.expenses.budgets.*'),
        },
        {
            title: trans('layouts.contractors.form.charges'),
            href: route('contractors.expenses.charges.index', {
                contractor: route().params.contractor,
            }),
            icon: WalletIcon,
            isActive: route().current('contractors.expenses.charges.*'),
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
