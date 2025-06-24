<script setup lang="ts">
import ExpenseCategoryCombobox from '@/components/expense/category/ExpenseCategoryCombobox.vue';
import ExpenseSubCategoryCombobox from '@/components/expense/sub-category/ExpenseSubCategoryCombobox.vue';
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
import { ExpenseItemFormData } from '@/composables';

defineProps<FormProps>();

const { form } = injectFormContext<ExpenseItemFormData>();
</script>

<template>
    <FormContent>
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.expense.category.name.one') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <ExpenseCategoryCombobox
                    v-model="form.expense_category"
                    :autofocus
                    @update:model-value="form.expense_sub_category = undefined"
                />
            </FormControl>
            <FormError :message="form.errors.expense_category_id" />
        </FormField>
        <FormField>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.expense.sub_category.name.one') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <ExpenseSubCategoryCombobox
                    v-model="form.expense_sub_category"
                    :expense-category-id="form.expense_category?.id"
                    @update:model-value="
                        form.expense_category = $event ? $event.expense_category : form.expense_category
                    "
                />
            </FormControl>
            <FormError :message="form.errors.expense_sub_category_id" />
        </FormField>
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.expense.item.fields.name') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <TextInput v-model="form.name" />
            </FormControl>
            <FormError :message="form.errors.name" />
        </FormField>
    </FormContent>
</template>
