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
import { computed } from 'vue';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.deals.commercials.index.title'),
                href: route('deals.commercials.index'),
            },
            {
                title: trans('pages.deals.commercials.edit.title'),
                href: route('deals.commercials.edit', { deal: route().params.deal }),
            },
        ],
    })),
});

const props = defineProps<CommercialDealFormProps>();
const form = useCommercialDealForm(props.deal);

const deal = computed(() => props.deal);

function submit() {
    const { deal } = props;
    form.put(route('deals.commercials.edit', { deal: deal! }));
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
                        <InertiaLink method="get" :href="route('deals.commercials.validate', { deal: deal! })">
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
