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
                title: trans('pages.employees.index.title'),
                href: route('employees.index'),
            },
            {
                title: trans('pages.employees.edit.title'),
                href: route('employees.edit', {
                    employee: route().params.employee,
                }),
            },
        ],
    })),
});

const sidebarNavItems = useRouterComputed((): NavItemHref[] =>
    [
        {
            title: trans('layouts.employees.form.edit'),
            href: route('employees.edit', {
                employee: route().params.employee,
            }),
            icon: SquarePenIcon,
            isActive: route().current('employees.edit'),
        },
        {
            title: trans('layouts.employees.form.budgets'),
            href: route('employees.expenses.budgets.index', {
                employee: route().params.employee,
            }),
            icon: BanknoteIcon,
            isActive: route().current('employees.expenses.budgets.*'),
        },
        {
            title: trans('layouts.employees.form.charges'),
            href: route('employees.expenses.charges.index', {
                employee: route().params.employee,
            }),
            icon: WalletIcon,
            isActive: route().current('employees.expenses.charges.*'),
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
