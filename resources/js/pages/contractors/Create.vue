<script setup lang="ts">
import ContractorForm from '@/components/contractor/ContractorForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import {
    Section,
    SectionContent,
    SectionDescription,
    SectionFooter,
    SectionHeader,
    SectionTitle,
} from '@/components/ui/custom/section';
import { useContractorForm, useLayout } from '@/composables';
import { AppLayout } from '@/layouts';
import { ContractorFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.contractors.index.title'),
                href: route('contractors.index'),
            },
            {
                title: trans('pages.contractors.create.title'),
                href: route('contractors.create'),
            },
        ],
    })),
});

defineProps<ContractorFormProps>();

const form = useContractorForm();

function submit() {
    form.post(route('contractors.create'));
}
</script>

<template>
    <Head :title="$t('pages.contractors.create.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.contractors.create.title') }}
                </SectionTitle>
                <SectionDescription>
                    {{ $t('pages.contractors.create.description') }}
                </SectionDescription>
            </SectionHeader>
            <SectionContent class="sm:flex">
                <ContractorForm autofocus />
            </SectionContent>
            <SectionFooter>
                <FormSubmitButton />
            </SectionFooter>
        </Section>
    </Form>
</template>
