<script setup lang="ts">
import CommercialDealValidate from '@/components/deal/commercial/CommercialDealValidate.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { Section, SectionContent, SectionFooter, SectionHeader } from '@/components/ui/custom/section';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { useCommercialDealValidateFrom, useLayout } from '@/composables';
import ClientFormLayout from '@/layouts/client/ClientFormLayout.vue';
import type { CommercialDealValidateProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { SaveIcon } from 'lucide-vue-next';

defineOptions({
    layout: useLayout(ClientFormLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.deals.commercials.index.title'),
                href: route('clients.commercials.index'),
            },
            {
                title: trans('pages.deals.commercials.edit.title'),
                href: route('clients.commercials.edit', { deal: route().params.deal, client: route().params.client }),
            },
            {
                title: route().params.deal,
                href: route('clients.commercials.edit', { deal: route().params.deal, client: route().params.client }),
            },
        ],
    })),
});

const props = defineProps<CommercialDealValidateProps>();
const form = useCommercialDealValidateFrom(props.deal, props.reference);

function submit() {
    form.post(route('clients.commercials.validate.process', { deal: props.deal, client: props.client! }));
}
</script>

<template>
    <Head :title="$t('pages.deals.commercials.validate.title', { name: props.deal.name })" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.deals.commercials.validate.title', { name: props.deal.name }) }}
                </SectionTitle>
            </SectionHeader>
            <SectionContent class="sm:flex">
                <CommercialDealValidate />
            </SectionContent>
            <SectionFooter>
                <FormSubmitButton>
                    <SaveIcon />
                    <CapitalizeText>
                        {{ $t('pages.deals.commercials.validate.save') }}
                    </CapitalizeText>
                </FormSubmitButton>
            </SectionFooter>
        </Section>
    </Form>
</template>
