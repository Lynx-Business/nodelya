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
                href: route('commercial.deals.index'),
            },
            {
                title: trans('pages.commercial_deals.create.title'),
                href: route('commercial.deals.edit', { deal: route().params.deal }),
            },
        ],
    })),
});

const props = defineProps<CommercialDealFormProps>();
const form = useCommercialDealForm(props.deal);

function submit() {
    const { deal } = props;
    form.put(route('commercial.deals.edit', { deal: deal! }));
}
</script>

<template>
    <Head :title="$t('pages.commercial_deals.edit.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.commercial_deals.edit.title') }}
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
