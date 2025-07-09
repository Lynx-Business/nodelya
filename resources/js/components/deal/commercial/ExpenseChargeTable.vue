<script setup lang="ts">
import ContractorCombobox from '@/components/contractor/ContractorCombobox.vue';
import ExpenseItemCombobox from '@/components/expense/item/ExpenseItemCombobox.vue';
import { Button } from '@/components/ui/button';
import {
    DataTable,
    DataTableBody,
    DataTableCell,
    DataTableContent,
    DataTableHead,
    DataTableHeadActions,
    DataTableHeader,
    DataTableRow,
    DataTableRowActions,
} from '@/components/ui/custom/data-table';
import DatePicker from '@/components/ui/custom/date-picker/DatePicker.vue';
import { FormControl, FormField, FormLabel, injectFormContext } from '@/components/ui/custom/form';
import { PriceInput } from '@/components/ui/custom/input';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { CommercialDealValidateFormData } from '@/composables';
import { PlusIcon, Trash2Icon } from 'lucide-vue-next';
import { ref } from 'vue';

const newCharge = ref({
    expense_item: undefined,
    amount: 0,
    charged_at: undefined,
    contractor: undefined,
});

const { form } = injectFormContext<CommercialDealValidateFormData>();

function addCharge() {
    if (!newCharge.value.expense_item || !newCharge.value.amount || !newCharge.value.charged_at) {
        return;
    }
    form.expense_charges.push({
        expense_item: newCharge.value.expense_item,
        amount: newCharge.value.amount,
        charged_at: newCharge.value.charged_at,
        contractor: newCharge.value.contractor,
    });
    newCharge.value = {
        expense_item: undefined,
        amount: 0,
        charged_at: undefined,
        contractor: undefined,
    };
}

function removeCharge(item: any) {
    const idx = form.expense_charges.indexOf(item);
    if (idx !== -1) form.expense_charges.splice(idx, 1);
}

function getScheduleError(index: number, field: string) {
    const key = `schedule_data.${index}.${field}`;
    return (form.errors as Record<string, string>)[key] || '';
}

const rowActions = [
    {
        type: 'callback' as const,
        label: 'Supprimer',
        icon: Trash2Icon,
        callback: (item: any) => removeCharge(item),
    },
];
</script>

<template>
    <div>
        <h4 class="mb-4 text-lg font-medium">{{ $t('components.deal.commercial.subcontracting_expense_title') }}</h4>
        <div class="mb-4 flex items-center gap-2">
            <FormField>
                <FormLabel>
                    <CapitalizeText> {{ $t('models.expense.budget.fields.expense_item') }} </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <ExpenseItemCombobox v-model="newCharge.expense_item" />
                </FormControl>
            </FormField>
            <FormField>
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('models.deal.billing.fields.amount') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <PriceInput v-model="newCharge.amount" :min="0" />
                </FormControl>
            </FormField>
            <FormField>
                <FormLabel>
                    <CapitalizeText> {{ $t('models.deal.commercial.fields.ordered_at') }} </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <DatePicker v-model="newCharge.charged_at" />
                </FormControl>
            </FormField>
            <FormField>
                <FormLabel>
                    <CapitalizeText> {{ $t('models.contractor.name.one') }}</CapitalizeText>
                </FormLabel>
                <FormControl>
                    <ContractorCombobox v-model="newCharge.contractor" />
                </FormControl>
            </FormField>
            <div class="flex items-center">
                <Button @click="addCharge" variant="ghost" size="icon" class="mt-4">
                    <PlusIcon />
                </Button>
            </div>
        </div>
        <DataTable v-slot="{ rows }" :data="form.expense_charges" :row-actions="rowActions">
            <DataTableContent tab="table">
                <DataTableHeader>
                    <DataTableRow>
                        <DataTableHead>{{ $t('models.expense.budget.fields.expense_item') }}</DataTableHead>
                        <DataTableHead>{{ $t('models.deal.commercial.fields.amount') }}</DataTableHead>
                        <DataTableHead>{{ $t('models.deal.commercial.fields.ordered_at') }}</DataTableHead>
                        <DataTableHead>{{ $t('models.contractor.name.one') }}</DataTableHead>
                        <DataTableHead>
                            <DataTableHeadActions />
                        </DataTableHead>
                    </DataTableRow>
                </DataTableHeader>
                <DataTableBody>
                    <DataTableRow v-for="(charge, index) in rows" :key="index" :item="charge">
                        <DataTableCell>
                            <FormField required>
                                <FormControl>
                                    <ExpenseItemCombobox v-model="charge.expense_item" />
                                </FormControl>
                                <FormError :message="getScheduleError(index, 'expense_item')" />
                            </FormField>
                        </DataTableCell>
                        <DataTableCell>
                            <FormField required>
                                <FormControl>
                                    <PriceInput v-model="charge.amount" :min="0" />
                                </FormControl>
                                <FormError :message="getScheduleError(index, 'amount')" />
                            </FormField>
                        </DataTableCell>
                        <DataTableCell>
                            <FormField required>
                                <FormControl>
                                    <DatePicker v-model="charge.charged_at" />
                                </FormControl>
                                <FormError :message="getScheduleError(index, 'charged_at')" />
                            </FormField>
                        </DataTableCell>
                        <DataTableCell>
                            <FormField required>
                                <FormControl>
                                    <ContractorCombobox v-model="charge.contractor" />
                                </FormControl>
                                <FormError :message="getScheduleError(index, 'contractor')" />
                            </FormField>
                        </DataTableCell>
                        <DataTableCell>
                            <DataTableRowActions />
                        </DataTableCell>
                    </DataTableRow>
                </DataTableBody>
            </DataTableContent>
        </DataTable>
    </div>
</template>
