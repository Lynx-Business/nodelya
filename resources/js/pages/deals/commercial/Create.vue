<script setup lang="ts">
import CommercialDealForm from '@/components/deal/commercial/CommercialDealForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { Section, SectionContent, SectionFooter, SectionHeader, SectionTitle } from '@/components/ui/custom/section';
import { useCommercialDealForm, useLayout } from '@/composables';
import { AppLayout } from '@/layouts';
import type { CommercialDealFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.commercial_deals.index.title'),
                href: route('deals.commercials.index'),
            },
            {
                title: trans('pages.commercial_deals.create.title'),
                href: route('deals.commercials.create'),
            },
        ],
    })),
});

defineProps<CommercialDealFormProps>();
const form = useCommercialDealForm();

function submit() {
    form.post(route('deals.commercials.store'));
}
</script>

<template>
    <Head :title="$t('pages.commercial_deals.create.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.commercial_deals.create.title') }}
                </SectionTitle>
            </SectionHeader>
            <SectionContent class="sm:flex">
                <CommercialDealForm />
            </SectionContent>
            <SectionFooter>
                <FormSubmitButton />
            </SectionFooter>
        </Section>
    </Form>
</template>
