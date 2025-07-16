<script setup lang="ts">
import { ResponsiveTabs, ResponsiveTabsTrigger } from '@/components/ui/custom/responsive-tabs';
import { Section, SectionContent } from '@/components/ui/custom/section';
import { Separator } from '@/components/ui/separator';
import { clearSessionFilters, useLayout, usePageProp, useRouterComputed } from '@/composables';
import { AppLayout } from '@/layouts';
import { NavItemHref } from '@/types';
import { trans } from 'laravel-vue-i18n';
import { ShoppingBagIcon, SquarePenIcon } from 'lucide-vue-next';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.clients.index.title'),
                href: route('clients.index'),
            },
            {
                title: usePageProp<string>('client.name', route().params.client).value,
                href: route('clients.edit', { client: route().params.client }),
            },
        ],
    })),
});

const sidebarNavItems = useRouterComputed((): NavItemHref[] =>
    [
        {
            title: trans('layouts.client.form.edit'),
            href: route('clients.edit', { client: route().params.client }),
            icon: SquarePenIcon,
            isActive: route().current('clients.edit'),
        },
        {
            title: trans('layouts.client.form.commercial'),
            href: route('clients.commercials.index', { client: route().params.client }),
            icon: ShoppingBagIcon,
            isActive: route().current('clients.commercials.*'),
        },
        {
            title: trans('layouts.client.form.billing'),
            href: route('clients.billings.index', { client: route().params.client }),
            icon: ShoppingBagIcon,
            isActive: route().current('clients.billings.*'),
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
                    <section class="space-y-8 *:not-[.w-full]:max-w-4xl">
                        <slot />
                    </section>
                </div>
            </div>
        </SectionContent>
    </Section>
</template>
