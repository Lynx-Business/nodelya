<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    FormContent,
    FormControl,
    FormError,
    FormField,
    FormLabel,
    injectFormContext,
} from '@/components/ui/custom/form';
import { PriceInput, TextInput } from '@/components/ui/custom/input';
import { CommercialDealValidateFormData, useLayout } from '@/composables';
import { AppLayout } from '@/layouts';
import type { CommercialDealValidateProps } from '@/types';
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

const props = defineProps<CommercialDealValidateProps>();

const { form } = injectFormContext<CommercialDealValidateFormData>();

const submit = () => {
    form.post(route('commercial.deals.validate.process', { deal: props.deal.id }));
};
</script>

<template>
    <form @submit.prevent="submit">
        <FormContent class="sm:grid-cols-2">
            <FormField>
                <FormLabel>Référence de commande</FormLabel>
                <FormControl>
                    <TextInput :model-value="form.reference" disabled />
                </FormControl>
            </FormField>

            <FormField required>
                <FormLabel>Montant validé</FormLabel>
                <FormControl>
                    <PriceInput v-model="form.amount" />
                </FormControl>
                <FormError :message="form.errors.amount" />
            </FormField>

            <div class="col-span-full">
                <Button type="submit" :disabled="form.processing"> Valider l'affaire </Button>
            </div>
        </FormContent>
    </form>
</template>
