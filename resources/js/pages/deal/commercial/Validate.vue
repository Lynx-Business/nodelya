<script setup lang="ts">
import CommercialDealValidate from '@/components/deal/commercial/CommercialDealValidate.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { Section, SectionContent, SectionFooter, SectionHeader } from '@/components/ui/custom/section';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { useCommercialDealValidateFrom, useLayout } from '@/composables';
import { AppLayout } from '@/layouts';
import type { CommercialDealValidateProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { SaveIcon } from 'lucide-vue-next';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.commercial_deals.index.title'),
                href: route('commercial.deals.index'),
            },
            {
                title: trans('pages.commercial_deals.edit.title'),
                href: route('commercial.deals.edit', { deal: route().params.deal }),
            },
            {
                title: route().params.deal,
                href: route('commercial.deals.edit', { deal: route().params.deal }),
            },
        ],
    })),
});

const props = defineProps<CommercialDealValidateProps>();
const form = useCommercialDealValidateFrom(props.deal, props.reference);

function submit() {
    form.post(route('commercial.deals.validate.process', { deal: props.deal.id }));
}
</script>

<template>
    <Head :title="$t('pages.commercial_deals.validate.title', { name: props.deal.name })" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.commercial_deals.validate.title', { name: props.deal.name }) }}
                </SectionTitle>
            </SectionHeader>
            <SectionContent class="sm:flex">
                <CommercialDealValidate />
            </SectionContent>
            <SectionFooter>
                <FormSubmitButton>
                    <SaveIcon />
                    <CapitalizeText>
                        {{ $t('pages.commercial_deals.validate.save') }}
                    </CapitalizeText>
                </FormSubmitButton>
            </SectionFooter>
        </Section>
    </Form>
</template>
