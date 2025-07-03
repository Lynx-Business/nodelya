<script setup lang="ts">
import { ResponsiveTabs, ResponsiveTabsTrigger } from '@/components/ui/custom/responsive-tabs';
import { Section, SectionContent, SectionHeader } from '@/components/ui/custom/section';
import { clearSessionFilters, usePageProp, useRouterComputed } from '@/composables';
import { Enum, ExpenseType, NavItemHref } from '@/types';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

type ExpenseModel = 'category' | 'item' | 'sub-category';
type Props = {
    model: ExpenseModel;
};
const props = defineProps<Props>();

function plural(model: ExpenseModel): string {
    switch (model) {
        case 'sub-category':
            return 'sub-categories';
        case 'item':
            return 'items';
        case 'category':
        default:
            return 'categories';
    }
}

const types = usePageProp<Enum<ExpenseType>[]>('expenseTypes', []);
const typeTabs = useRouterComputed((): NavItemHref[] =>
    types.value.map((expenseType) => {
        const href = route(`teams.expenses.${plural(props.model)}.index`, {
            team: route().params.team,
            expenseType: expenseType.value,
        });

        return {
            href,
            title: expenseType.label,
            isActive: route().params.expenseType === expenseType.value,
            options: {
                onBefore: () => clearSessionFilters(href),
            },
        };
    }),
);

const models = computed((): ExpenseModel[] => ['category', 'sub-category', 'item']);
const modelTabs = useRouterComputed((): NavItemHref[] =>
    models.value.map((model) => {
        const href = route(`teams.expenses.${plural(model)}.index`, {
            team: route().params.team,
            expenseType: route().params.expenseType,
        });

        return {
            href,
            title: trans(`models.expense.${model.replaceAll('-', '_')}.name.many`),
            isActive: model === props.model,
            options: {
                onBefore: () => clearSessionFilters(href),
            },
        };
    }),
);
</script>

<template>
    <Section class="w-full p-0!">
        <SectionHeader class="px-0!">
            <ResponsiveTabs :tabs="typeTabs">
                <ResponsiveTabsTrigger />
            </ResponsiveTabs>
            <ResponsiveTabs :tabs="modelTabs">
                <ResponsiveTabsTrigger />
            </ResponsiveTabs>
        </SectionHeader>
        <SectionContent class="px-0!">
            <slot />
        </SectionContent>
    </Section>
</template>
