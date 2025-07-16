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
import { FormControl, FormError, FormField, FormLabel } from '@/components/ui/custom/form';
import { PriceInput } from '@/components/ui/custom/input';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { ExpenseChargeResource, PartialNullable } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { PlusIcon, Trash2Icon } from 'lucide-vue-next';

interface Props {
    errors?: Record<string, string>;
}

type ChargeType = PartialNullable<ExpenseChargeResource, 'id'>;

const errors = defineModel<Record<string, string>>('errors');
const charges = defineModel<ChargeType[]>('charges');

const newChargeForm = useForm<ChargeType>({
    id: undefined,
    expense_item: undefined,
    expense_item_id: 0,
    amount: 0,
    charged_at: '',
    contractor: undefined,
});

function addCharge() {
    if (!newChargeForm.expense_item || !newChargeForm.amount || !newChargeForm.charged_at) {
        return;
    }
    charges.value?.push({
        expense_item: newChargeForm.expense_item,
        expense_item_id: newChargeForm.expense_item.id,
        amount: newChargeForm.amount,
        charged_at: newChargeForm.charged_at,
        contractor: newChargeForm.contractor,
    });
    newChargeForm.reset();
}

function removeCharge(item: ChargeType) {
    const idx = charges.value?.indexOf(item);
    if (idx !== -1 && idx !== undefined && charges.value) {
        charges.value.splice(idx, 1);
    }
}

function getScheduleError(index: number, field: string) {
    const key = `expense_charges.${index}.${field}`;
    return (errors.value as Record<string, string>)?.[key] || '';
}

const rowActions = [
    {
        type: 'callback' as const,
        label: trans('delete'),
        icon: Trash2Icon,
        callback: (item: ChargeType) => removeCharge(item),
    },
];
</script>

<template>
    <div>
        <h4 class="mb-4 text-lg font-medium">{{ $t('components.deal.billing.subcontracting_expense_title') }}</h4>
        <div class="mb-4 flex items-center gap-2">
            <FormField>
                <FormLabel>
                    <CapitalizeText> {{ $t('models.expense.budget.fields.expense_item') }} </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <ExpenseItemCombobox v-model="newChargeForm.expense_item" />
                </FormControl>
            </FormField>
            <FormField>
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('models.deal.billing.fields.amount') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <PriceInput v-model="newChargeForm.amount" :min="0" />
                </FormControl>
            </FormField>
            <FormField>
                <FormLabel>
                    <CapitalizeText> {{ $t('models.deal.billing.fields.ordered_at') }} </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <DatePicker v-model="newChargeForm.charged_at" />
                </FormControl>
            </FormField>
            <FormField>
                <FormLabel>
                    <CapitalizeText> {{ $t('models.contractor.name.one') }}</CapitalizeText>
                </FormLabel>
                <FormControl>
                    <ContractorCombobox
                        :model-value="newChargeForm.contractor ?? undefined"
                        @update:model-value="(val) => (newChargeForm.contractor = val)"
                    />
                </FormControl>
            </FormField>
            <div class="flex items-center">
                <Button @click="addCharge" variant="ghost" size="icon" class="mt-4">
                    <PlusIcon />
                </Button>
            </div>
        </div>
        <DataTable v-slot="{ rows }" :data="charges" :row-actions="rowActions">
            <DataTableContent tab="table">
                <DataTableHeader>
                    <DataTableRow>
                        <DataTableHead>{{ $t('models.expense.budget.fields.expense_item') }}</DataTableHead>
                        <DataTableHead>{{ $t('models.deal.billing.fields.amount') }}</DataTableHead>
                        <DataTableHead>{{ $t('models.deal.billing.fields.ordered_at') }}</DataTableHead>
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
                                    <ContractorCombobox
                                        :model-value="charge.contractor ?? undefined"
                                        @update:model-value="newChargeForm.contractor = $event"
                                    />
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
