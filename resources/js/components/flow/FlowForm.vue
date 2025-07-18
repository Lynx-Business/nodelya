<script setup lang="ts">
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
    DataTableRowCallbackAction,
} from '@/components/ui/custom/data-table';
import { DatePicker } from '@/components/ui/custom/date-picker';
import {
    FormContent,
    FormControl,
    FormError,
    FormField,
    FormLabel,
    injectFormContext,
} from '@/components/ui/custom/form';

import { PriceInput } from '@/components/ui/custom/input';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { FlowFormData } from '@/composables/forms/flows/useFlowForm';
import type { ChargeFrequency, FlowChargeData } from '@/types/backend';
import { useForm } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { Trash2Icon } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '../ui/button';
import { Checkbox } from '../ui/checkbox';
import { EnumCombobox } from '../ui/custom/combobox';
import FlowCategoryCombobox from './FlowCategoryCombobox.vue';

const { form } = injectFormContext<FlowFormData>();

const isRecurrent = ref(false);
const frequency = ref(undefined);
const end_date = ref(undefined);

const newCharge = useForm<FlowChargeData>({
    category: undefined,
    category_id: 0,
    date: '',
    amount: 0,
});

function addCharge() {
    if (!newCharge.category || !newCharge.date || !newCharge.amount) return;

    // Recurrent expense
    if (isRecurrent.value && frequency.value && end_date.value) {
        const dates = generateRecurringDates(newCharge.date, frequency.value, end_date.value);

        dates.forEach((date) => {
            form.charges.push({
                category: newCharge.category,
                category_id: 0,
                date: date,
                amount: newCharge.amount,
            });
        });
    }
    // One-time expense
    else {
        form.charges.push({
            category: newCharge.category,
            date: newCharge.date,
            category_id: 0,
            amount: newCharge.amount,
        });
    }

    newCharge.reset();
    isRecurrent.value = false;
    frequency.value = undefined;
    end_date.value = undefined;
}

function removeCharge(item: FlowChargeData) {
    const index = form.charges.indexOf(item);
    if (index !== -1) {
        form.charges.splice(index, 1);
    }
}

const rowActions: DataTableRowCallbackAction<FlowChargeData>[] = [
    {
        type: 'callback',
        label: trans('delete'),
        icon: Trash2Icon,
        disabled: (item) => false,
        callback: (item) => removeCharge(item),
    },
];

function getScheduleError(index: number, field: string) {
    const key = `charges.${index}.${field}`;
    return (form.errors as Record<string, string>)[key] || '';
}

function generateRecurringDates(startDate: string, frequency: ChargeFrequency, endDate: string): string[] {
    const dates: string[] = [];
    const start = new Date(startDate);
    const end = new Date(endDate);
    let current = new Date(start);

    while (current <= end) {
        dates.push(current.toISOString().split('T')[0]);

        switch (frequency) {
            case 'month':
                current.setMonth(current.getMonth() + 1);
                break;
            case 'quarter':
                current.setMonth(current.getMonth() + 3);
                break;
            case 'semester':
                current.setMonth(current.getMonth() + 6);
                break;
        }
    }

    return dates;
}
</script>

<template>
    <FormContent class="sm:grid-cols-3">
        <FormField>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.flow.fields.category') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <FlowCategoryCombobox v-model="newCharge.category" />
            </FormControl>
        </FormField>

        <FormField>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.flow.fields.date') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <DatePicker v-model="newCharge.date" />
            </FormControl>
        </FormField>

        <FormField>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.flow.fields.amount') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <PriceInput v-model="newCharge.amount" />
            </FormControl>
        </FormField>

        <FormField>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('components.flow.recurrent_charge') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <Checkbox id="recurrent" v-model="isRecurrent" />
            </FormControl>
        </FormField>
        <template v-if="isRecurrent">
            <FormField>
                <FormLabel>
                    <CapitalizeText> Fréquence </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <EnumCombobox v-model="frequency" data="charge_frequency" />
                </FormControl>
            </FormField>

            <FormField>
                <FormLabel>
                    <CapitalizeText> Date de fin </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <DatePicker v-model="end_date" />
                </FormControl>
            </FormField>
        </template>

        <div class="col-span-full mt-4 flex justify-end">
            <Button @click="addCharge">
                {{ $t('add') }}
            </Button>
        </div>

        <div class="col-span-full mt-6">
            <h3 class="mb-4 font-medium">{{ $t('components.flow.list.title') }}</h3>
            <DataTable v-slot="{ rows }" :data="form.charges" :row-actions="rowActions">
                <DataTableContent tab="table">
                    <DataTableHeader>
                        <DataTableRow>
                            <DataTableHead>{{ $t('models.flow.fields.category') }}</DataTableHead>
                            <DataTableHead>{{ $t('models.flow.fields.date') }}</DataTableHead>
                            <DataTableHead>{{ $t('models.flow.fields.amount') }}</DataTableHead>
                            <DataTableHead>
                                <DataTableHeadActions />
                            </DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow v-for="(item, index) in rows" :key="index" :item>
                            <DataTableCell>
                                <FormField required>
                                    <FormControl>
                                        <FlowCategoryCombobox v-model="item.category" />
                                    </FormControl>
                                    <FormError :message="getScheduleError(index, 'date')" />
                                </FormField>
                            </DataTableCell>
                            <DataTableCell>
                                <FormField required>
                                    <FormControl>
                                        <DatePicker v-model="item.date" />
                                    </FormControl>
                                    <FormError :message="getScheduleError(index, 'date')" />
                                </FormField>
                            </DataTableCell>
                            <DataTableCell>
                                <FormField required>
                                    <FormControl>
                                        <PriceInput v-model="item.amount" />
                                    </FormControl>
                                    <FormError :message="getScheduleError(index, 'amount')" />
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
    </FormContent>
</template>
