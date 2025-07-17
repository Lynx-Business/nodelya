<script setup lang="ts">
import AccountingPeriodMonthSelect from '@/components/accounting-period/AccountingPeriodMonthSelect.vue';
import ExpenseCategoryCombobox from '@/components/expense/category/ExpenseCategoryCombobox.vue';
import ExpenseItemCombobox from '@/components/expense/item/ExpenseItemCombobox.vue';
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
import { ExpenseChargeFormData, usePageProp } from '@/composables';
import { AccountingPeriodResource, ExpenseCategoryResource, ExpenseSubCategoryResource } from '@/types';
import { WhenVisible } from '@inertiajs/vue3';

defineProps<FormProps>();

const { form } = injectFormContext<ExpenseChargeFormData>();

const accountingPeriod = usePageProp<AccountingPeriodResource>('accountingPeriod');
const categories = usePageProp<ExpenseCategoryResource[]>('expenseCategories', []);
const subCategories = usePageProp<ExpenseSubCategoryResource[]>('expenseSubCategories', []);

function onCategoryChange() {
    form.expense_item = undefined;
    form.expense_sub_category = undefined;
}
function onSubCategoryChange() {
    form.expense_item = undefined;
    form.expense_category =
        categories.value.find((category) => category.id === form.expense_sub_category?.expense_category_id) ??
        form.expense_category;
}
function onItemChange() {
    form.expense_sub_category =
        subCategories.value.find((subCategory) => subCategory.id === form.expense_item?.expense_sub_category_id) ??
        form.expense_sub_category;
    form.expense_category =
        categories.value.find((category) => category.id === form.expense_item?.expense_category_id) ??
        form.expense_category;
}
</script>

<template>
    <WhenVisible :data="['expenseCategories', 'expenseSubCategories']">
        <FormContent class="sm:grid-cols-2">
            <FormField required>
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('models.expense.item.fields.expense_category') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <ExpenseCategoryCombobox
                        v-model="form.expense_category"
                        :autofocus
                        @update:model-value="onCategoryChange()"
                    />
                </FormControl>
            </FormField>
            <FormField>
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('models.expense.item.fields.expense_sub_category') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <ExpenseSubCategoryCombobox
                        v-model="form.expense_sub_category"
                        :expense-category-id="form.expense_category?.id"
                        @update:model-value="onSubCategoryChange()"
                    />
                </FormControl>
            </FormField>
            <FormField required>
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('models.expense.charge.fields.expense_item') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <ExpenseItemCombobox
                        v-model="form.expense_item"
                        :expense-category-id="form.expense_category?.id"
                        @update:model-value="onItemChange()"
                    />
                </FormControl>
                <FormError :message="form.errors.expense_item_id" />
            </FormField>
            <FormField required>
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('models.expense.charge.fields.charged_at') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <AccountingPeriodMonthSelect v-model="form.charged_at" :accounting-period="accountingPeriod" />
                </FormControl>
                <FormError :message="form.errors.charged_at" />
            </FormField>
            <FormField required class="col-span-full">
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('models.expense.charge.fields.amount') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <TextInput v-model="form.amount" min="0" step="0.01" type="number" />
                </FormControl>
                <FormError :message="form.errors.amount" />
            </FormField>
        </FormContent>
    </WhenVisible>
</template>
