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
import { EmployeeEndsAtFormData } from '@/composables';

defineProps<FormProps>();

const { form } = injectFormContext<EmployeeEndsAtFormData>();
</script>

<template>
    <FormContent>
        <FormField required :disabled>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.employee.fields.ends_at') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <DatePicker v-model="form.ends_at" />
            </FormControl>
            <FormError :message="form.errors.ends_at" />
        </FormField>
    </FormContent>
</template>
