<script setup lang="ts">
import FlowCategoryCombobox from '@/components/flow/FlowCategoryCombobox.vue';
import {
    DataTable,
    DataTableBody,
    DataTableCell,
    DataTableContent,
    DataTableHead,
    DataTableHeader,
    DataTableRow,
} from '@/components/ui/custom/data-table';
import { DatePicker } from '@/components/ui/custom/date-picker';
import { FormContent, FormControl, FormField, FormLabel, injectFormContext } from '@/components/ui/custom/form';
import { PriceInput } from '@/components/ui/custom/input';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { FlowFormData } from '@/composables/forms/flows/useFlowForm';
import type { FlowChargeData } from '@/types/backend';
import { useForm } from '@inertiajs/vue3';

const { form } = injectFormContext<FlowFormData>();

const newCharge = useForm<Partial<FlowChargeData>>({
    category_id: undefined,
    category_name: undefined,
    date: '',
    amount: 0,
});

function addCharge() {
    if ((!newCharge.category_id && !newCharge.category_name) || !newCharge.date || !newCharge.amount) return;
    form.charges.push({
        category_id: newCharge.category_id,
        category_name: newCharge.category_name,
        date: newCharge.date,
        amount: newCharge.amount,
    });
    newCharge.reset();
}

function removeCharge(index: number) {
    form.charges.splice(index, 1);
}
</script>

<template>
    <FormContent class="sm:grid-cols-3">
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.flow.fields.category') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <FlowCategoryCombobox v-model="newCharge.category_id" />
            </FormControl>
        </FormField>

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.flow.fields.date') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <DatePicker v-model="newCharge.date" />
            </FormControl>
        </FormField>

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.flow.fields.amount') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <PriceInput v-model="newCharge.amount" :min="0" />
            </FormControl>
        </FormField>

        <div class="col-span-full mt-4 flex justify-end">
            <button type="button" class="btn btn-primary" @click="addCharge">
                {{ $t('actions.add_charge') }}
            </button>
        </div>

        <div class="col-span-full mt-6">
            <h3 class="mb-4 text-lg font-medium">{{ $t('models.flow.charge.list') }}</h3>
            <DataTable v-slot="{ rows }" :data="form.charges">
                <DataTableContent tab="table">
                    <DataTableHeader>
                        <DataTableRow>
                            <DataTableHead>{{ $t('models.flow.charge.fields.category') }}</DataTableHead>
                            <DataTableHead>{{ $t('models.flow.charge.fields.date') }}</DataTableHead>
                            <DataTableHead>{{ $t('models.flow.charge.fields.amount') }}</DataTableHead>
                            <DataTableHead></DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow v-for="(item, index) in rows" :key="index" :item>
                            <DataTableCell>
                                {{ item.category_name || item.category_id }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ item.date }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ item.amount }}
                            </DataTableCell>
                            <DataTableCell>
                                <button type="button" class="btn btn-danger btn-xs" @click="removeCharge(index)">
                                    {{ $t('actions.delete') }}
                                </button>
                            </DataTableCell>
                        </DataTableRow>
                    </DataTableBody>
                </DataTableContent>
            </DataTable>
        </div>
    </FormContent>
</template>
