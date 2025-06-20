<script lang="ts">
export type FormContext<TForm extends FormDataType> = {
    form: InertiaForm<TForm>;
    disabled: Ref<boolean>;
};
export function injectFormContext<TForm extends FormDataType>(fallback?: FormContext<TForm>): FormContext<TForm> {
    const context = inject('FormContext', fallback);

    if (!context) {
        throw new Error(`Injection \`FormContext\` not found. Component must be used within a \`Form\``);
    }

    return context;
}
export function provideFormContext<TForm extends FormDataType>(contextValue: FormContext<TForm>) {
    return provide('FormContext', contextValue);
}
</script>

<script setup lang="ts" generic="TForm extends FormDataType">
import { cn } from '@/lib/utils';
import { FormDataType } from '@/types';
import { InertiaForm } from '@inertiajs/vue3';
import { computed, HTMLAttributes, inject, provide, Ref } from 'vue';

type Props = {
    form?: InertiaForm<TForm>;
    disabled?: boolean;
    class?: HTMLAttributes['class'];
};
const props = defineProps<Props>();

type Emits = {
    submit: [];
};
defineEmits<Emits>();

const disabled = computed((): boolean => props.disabled);

if (props.form) {
    provideFormContext({
        form: props.form,
        disabled,
    });
}
</script>

<template>
    <form :class="cn('', props.class)" @submit.prevent="$emit('submit')">
        <slot />
    </form>
</template>
