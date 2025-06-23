<script setup lang="ts">
import { InertiaCombobox } from '@/components/ui/custom/combobox';
import {
    FormContent,
    FormControl,
    FormError,
    FormField,
    FormLabel,
    FormProps,
    injectFormContext,
} from '@/components/ui/custom/form';
import { TextInput } from '@/components/ui/custom/input';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { ExpenseSubCategoryFormData } from '@/composables';

defineProps<FormProps>();

const { form } = injectFormContext<ExpenseSubCategoryFormData>();
</script>

<template>
    <FormContent>
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.expense.sub_category.fields.expense_category') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <InertiaCombobox v-model="form.expense_category" data="expenseCategories" by="id" label="name" />
            </FormControl>
            <FormError :message="form.errors.expense_category_id" />
        </FormField>
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.expense.sub_category.fields.name') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <TextInput v-model="form.name" />
            </FormControl>
            <FormError :message="form.errors.name" />
        </FormField>
    </FormContent>
</template>
