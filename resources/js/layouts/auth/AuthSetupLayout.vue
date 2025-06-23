<script setup lang="ts">
import AppShell from '@/components/app/AppShell.vue';
import {
    Stepper,
    StepperDescription,
    StepperIndicator,
    StepperItem,
    StepperSeparator,
    StepperTitle,
} from '@/components/ui/stepper';
import { useAutofocus, useLayout, useRouterComputed } from '@/composables';
import { trans } from 'laravel-vue-i18n';
import { BuildingIcon, CalendarRangeIcon, LucideIcon } from 'lucide-vue-next';
import AuthSimpleLayout from './AuthSimpleLayout.vue';

defineOptions({
    layout: useLayout(AuthSimpleLayout, () => ({})),
});

type Props = {
    step: number;
};
defineProps<Props>();

type Step = {
    icon: LucideIcon;
    title: string;
    description: string;
};
const steps = useRouterComputed((): Step[] => [
    {
        icon: BuildingIcon,
        title: trans('pages.auth.setup.step_one.title'),
        description: trans('pages.auth.setup.step_one.description'),
    },
    {
        icon: CalendarRangeIcon,
        title: trans('pages.auth.setup.step_two.title'),
        description: trans('pages.auth.setup.step_two.description'),
    },
]);

useAutofocus();
</script>

<template>
    <div>
        <Stepper class="flex w-full items-start gap-2" :model-value="step">
            <StepperItem
                class="relative flex w-full flex-col items-center justify-center"
                v-for="(step, index) in steps"
                :key="index"
                :step="index + 1"
            >
                <StepperSeparator
                    class="bg-muted group-data-[state=completed]:bg-primary absolute top-5 right-[calc(-50%+10px)] left-[calc(50%+20px)] block h-0.5 shrink-0 rounded-full"
                    v-if="index < steps.length - 1"
                />
                <StepperIndicator class="size-10">
                    <component class="size-6" :is="step.icon" />
                </StepperIndicator>

                <div class="mt-5 flex flex-col items-center text-center">
                    <StepperTitle>
                        {{ step.title }}
                    </StepperTitle>
                    <StepperDescription>
                        {{ step.description }}
                    </StepperDescription>
                </div>
            </StepperItem>
        </Stepper>
        <div class="mx-auto max-w-sm">
            <AppShell>
                <slot />
            </AppShell>
        </div>
    </div>
</template>
