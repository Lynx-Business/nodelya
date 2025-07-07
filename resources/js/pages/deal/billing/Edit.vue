<script setup lang="ts">
import BillingDealForm from '@/components/deal/Billing/BillingDealForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { Section, SectionContent, SectionFooter, SectionHeader, SectionTitle } from '@/components/ui/custom/section';
import { useBillingDealForm, useLayout } from '@/composables';
import { AppLayout } from '@/layouts';
import { BillingDealFormProps } from '@/types';

import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.billing_deals.index.title'),
                href: route('deals.billings.index'),
            },
            {
                title: trans('pages.billing_deals.edit.title'),
                href: route('deals.billings.edit', { deal: route().params.deal }),
            },
        ],
    })),
});

const props = defineProps<BillingDealFormProps>();
const form = useBillingDealForm(props.deal);

function submit() {
    const { deal } = props;
    form.put(route('deals.billings.edit', { deal: deal! }));
}
</script>

<template>
    <Head :title="$t('pages.billing_deals.edit.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle class="flex items-center justify-between gap-4">
                    <span>
                        {{ $t('pages.billing_deals.edit.title') }}
                    </span>
                </SectionTitle>
            </SectionHeader>
            <SectionContent class="sm:flex">
                <BillingDealForm />
            </SectionContent>
            <SectionFooter>
                <FormSubmitButton />
            </SectionFooter>
        </Section>
    </Form>
</template>
