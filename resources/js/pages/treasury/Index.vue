<script setup lang="ts">
import TreasuryChartTooltip from '@/components/treasury/TreasuryChartTooltip.vue';
import { Button } from '@/components/ui/button';
import { LineChart } from '@/components/ui/chart-line';
import { Form, FormContent, FormControl, FormField, FormLabel } from '@/components/ui/custom/form';
import { PriceInput } from '@/components/ui/custom/input';
import { LoadingIcon } from '@/components/ui/custom/loading';
import { Section, SectionContent } from '@/components/ui/custom/section';
import { useComputedForm, useFilters, useFormatter, useLayout, useParser } from '@/composables';
import { AppLayout } from '@/layouts';
import { TreasuryIndexProps, TreasuryIndexRequest } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { ChevronLeftIcon, ChevronRightIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.treasury.index.title'),
                href: route('treasury.index'),
            },
        ],
    })),
});

const props = defineProps<TreasuryIndexProps>();

const parse = useParser();
const format = useFormatter();

const form = useComputedForm({
    amount: 0,
});
const loading = ref(false);
const filters = useFilters<TreasuryIndexRequest>(
    route('treasury.index', {}),
    {
        starts_at: props.request.starts_at,
    },
    {
        only: ['months'],
        immediate: true,
        onBefore: () => (loading.value = true),
        onFinish: () => (loading.value = false),
    },
);
const startLabel = computed((): string => {
    const start = parse.toDate(filters.starts_at);
    if (!start) {
        return '';
    }
    return start.month === 0 ? `${start.year}` : `${start.year}-${start.year + 1}`;
});

const realAmountField = trans('pages.treasury.index.real_amount');
const plannedAmountField = trans('pages.treasury.index.planned_amount');
const data = computed(
    () =>
        props.months?.reduce(
            (months, month, index) => {
                const date = format.unix(month.date);
                if (index === 0) {
                    months.push({
                        date,
                        [realAmountField]: month.real_amount + form.amount,
                        [plannedAmountField]: month.planned_amount + form.amount,
                    });
                } else {
                    months.push({
                        date,
                        [realAmountField]: month.real_amount + months[index - 1][realAmountField],
                        [plannedAmountField]: month.planned_amount + months[index - 1][realAmountField],
                    });
                }

                return months;
            },
            [] as Record<string, number>[],
        ) ?? [],
);
</script>

<template>
    <Head :title="$t('pages.treasury.index.title')" />

    <Section class="container-full">
        <SectionContent>
            <Form :form>
                <FormContent class="sm:flex sm:justify-between">
                    <FormField>
                        <FormLabel>
                            {{ $t('pages.treasury.index.base_amount') }}
                        </FormLabel>
                        <FormControl>
                            <PriceInput v-model="form.amount" />
                        </FormControl>
                    </FormField>
                    <div class="flex items-center gap-2">
                        <Button variant="outline" size="icon">
                            <ChevronLeftIcon />
                        </Button>
                        {{ startLabel }}
                        <Button variant="outline" size="icon">
                            <ChevronRightIcon />
                        </Button>
                    </div>
                </FormContent>
            </Form>
        </SectionContent>
        <SectionContent class="relative grid">
            <LineChart
                :data
                index="date"
                :categories="[realAmountField, plannedAmountField]"
                :custom-tooltip="TreasuryChartTooltip"
                :x-formatter="
                    (tick) => {
                        if (!props.months) {
                            return '';
                        }

                        return typeof tick === 'number'
                            ? format.date(parse.toDate(request.starts_at)?.add({ months: tick }), {
                                  month: 'short',
                                  year: '2-digit',
                              })
                            : '';
                    }
                "
                :y-formatter="
                    (tick) => {
                        if (!props.months) {
                            return '';
                        }

                        return typeof tick === 'number' ? format.price(tick) : '';
                    }
                "
            />
            <div v-if="loading" class="absolute inset-0 grid place-items-center">
                <LoadingIcon variant="primary" />
            </div>
        </SectionContent>
    </Section>
</template>
