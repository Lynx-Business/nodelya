<script setup lang="ts">
import {
    FormContent,
    FormControl,
    FormError,
    FormField,
    FormLabel,
    injectFormContext,
} from '@/components/ui/custom/form';
import { PriceInput, TextInput } from '@/components/ui/custom/input';
import { CommercialDealValidateFormData } from '@/composables';

const { form } = injectFormContext<CommercialDealValidateFormData>();
</script>

<template>
    <FormContent class="sm:grid-cols-2">
        <FormField>
            <FormLabel>Référence de commande</FormLabel>
            <FormControl>
                <TextInput :model-value="form.reference" disabled />
            </FormControl>
        </FormField>

        <FormField required>
            <FormLabel>Montant</FormLabel>
            <FormControl>
                <PriceInput v-model="form.amount" />
            </FormControl>
            <FormError :message="form.errors.amount" />
        </FormField>
    </FormContent>
</template>
