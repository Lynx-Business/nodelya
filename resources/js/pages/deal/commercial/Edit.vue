<script setup lang="ts">
import CommercialDealForm from '@/components/deal/commercial/CommercialDealForm.vue';
import { Button } from '@/components/ui/button';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { InertiaLink } from '@/components/ui/custom/link';
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
                title: trans('pages.commercial_deals.edit.title'),
                href: route('deals.commercials.edit', { deal: route().params.deal }),
            },
        ],
    })),
});

const props = defineProps<CommercialDealFormProps>();
const form = useCommercialDealForm(props.deal);

function submit() {
    const { deal } = props;
    form.put(route('deals.commercials.edit', { deal: deal! }));
}
</script>

<template>
    <Head :title="$t('pages.commercial_deals.edit.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle class="flex items-center justify-between gap-4">
                    <span>
                        {{ $t('pages.commercial_deals.edit.title') }}
                    </span>
                    <Button class="text-right">
                        <InertiaLink method="get" :href="route('deals.commercials.validate', { deal: deal! })">
                            {{ $t('pages.commercial_deals.edit.validate_button') }}
                        </InertiaLink>
                    </Button>
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
