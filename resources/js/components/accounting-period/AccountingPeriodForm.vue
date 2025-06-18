<script setup lang="ts">
import { DatePicker } from '@/components/ui/custom/date-picker';
import {
    FormContent,
    FormControl,
    FormError,
    FormField,
    FormLabel,
    FormProps,
    injectFormContext,
} from '@/components/ui/custom/form';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { AccountingPeriodFormData } from '@/composables';

defineProps<FormProps>();

const { form } = injectFormContext<AccountingPeriodFormData>();
</script>

<template>
    <FormContent>
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.accounting_period.fields.starts_at') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <DatePicker v-model="form.starts_at" :autofocus />
            </FormControl>
            <FormError :message="form.errors.starts_at" />
        </FormField>
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.accounting_period.fields.ends_at') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <DatePicker v-model="form.ends_at" />
            </FormControl>
            <FormError :message="form.errors.ends_at" />
        </FormField>
    </FormContent>
</template>
