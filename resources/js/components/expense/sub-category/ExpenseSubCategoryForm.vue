<script setup lang="ts">
import ExpenseCategoryCombobox from '@/components/expense/category/ExpenseCategoryCombobox.vue';
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
                <ExpenseCategoryCombobox v-model="form.expense_category" :autofocus />
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
