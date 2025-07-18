<script setup lang="ts">
import FlowForm from '@/components/flow/FlowForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { Section, SectionContent, SectionFooter, SectionHeader, SectionTitle } from '@/components/ui/custom/section';
import { useLayout } from '@/composables';
import { useFlowForm } from '@/composables/forms/flows/useFlowForm';
import { AppLayout } from '@/layouts';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.flows.index.title'),
                href: route('flows.index'),
            },
            {
                title: trans('pages.flows.create.title'),
                href: route('flows.create'),
            },
        ],
    })),
});

const form = useFlowForm();

function submit() {
    form.post(route('flows.store'));
}
</script>

<template>
    <Head :title="$t('pages.flows.create.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.flows.create.title') }}
                </SectionTitle>
            </SectionHeader>
            <SectionContent class="sm:flex">
                <FlowForm />
            </SectionContent>
            <SectionFooter>
                <FormSubmitButton />
            </SectionFooter>
        </Section>
    </Form>
</template>
