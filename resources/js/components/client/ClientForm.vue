<script setup lang="ts">
import {
    FormContent,
    FormControl,
    FormError,
    FormField,
    FormLabel,
    injectFormContext,
} from '@/components/ui/custom/form';
import { TextInput } from '@/components/ui/custom/input';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { ClientFormData } from '@/composables';

const { form } = injectFormContext<ClientFormData>();
</script>

<template>
    <FormContent class="sm:grid-cols-2">
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.client.fields.name') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <TextInput v-model="form.name" />
            </FormControl>
            <FormError :message="form.errors.name" />
        </FormField>

        <div class="col-span-full grid grid-cols-1 gap-4 md:grid-cols-2">
            <FormField class="col-span-full" required>
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('data.address.fields.address') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <TextInput v-model="form.address.address" />
                </FormControl>
                <FormError :message="form.errors['address.address']" />
            </FormField>

            <FormField class="col-span-full">
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('data.address.fields.address_complement') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <TextInput v-model="form.address.address_complement" />
                </FormControl>
                <FormError :message="form.errors['address.address_complement']" />
            </FormField>

            <FormField required>
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('data.address.fields.city') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <TextInput v-model="form.address.city" />
                </FormControl>
                <FormError :message="form.errors['address.city']" />
            </FormField>

            <FormField required>
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('data.address.fields.postal_code') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <TextInput v-model="form.address.postal_code" />
                </FormControl>
                <FormError :message="form.errors['address.postal_code']" />
            </FormField>

            <FormField class="col-span-full" required>
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('data.address.fields.country') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <TextInput v-model="form.address.country" />
                </FormControl>
                <FormError :message="form.errors['address.country']" />
            </FormField>
        </div>
    </FormContent>
</template>
