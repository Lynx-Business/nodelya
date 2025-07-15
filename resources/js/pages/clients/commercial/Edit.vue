<script setup lang="ts">
import CommercialDealForm from '@/components/deal/commercial/CommercialDealForm.vue';
import { Button } from '@/components/ui/button';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { InertiaLink } from '@/components/ui/custom/link';
import { Section, SectionContent, SectionFooter, SectionHeader, SectionTitle } from '@/components/ui/custom/section';
import { useCommercialDealForm, useLayout } from '@/composables';
import ClientFormLayout from '@/layouts/client/ClientFormLayout.vue';
import type { CommercialDealFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

defineOptions({
    layout: useLayout(ClientFormLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.deals.commercials.index.title'),
                href: route('clients.commercials.index', { client: route().params.client }),
            },
            {
                title: trans('pages.deals.commercials.edit.title'),
                href: route('clients.commercials.edit', { deal: route().params.deal, client: route().params.client }),
            },
        ],
    })),
});

const props = defineProps<CommercialDealFormProps>();
const form = useCommercialDealForm(props.deal);

const deal = computed(() => props.deal);

function submit() {
    const { deal } = props;
    form.put(route('clients.commercials.edit', { deal: deal!, client: props.client! }));
}
</script>

<template>
    <Head :title="$t('pages.deals.commercials.edit.title')" />

    <Form :disabled="!deal?.can_update" :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle class="flex items-center justify-between gap-4">
                    <span>
                        {{ $t('pages.deals.commercials.edit.title') }}
                    </span>
                    <Button class="text-right" v-if="deal?.can_update">
                        <InertiaLink
                            method="get"
                            :href="route('clients.commercials.validate', { deal: deal!, client: client! })"
                        >
                            {{ $t('pages.deals.commercials.edit.validate_button') }}
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
