<script setup lang="ts">
import ClientCombobox from '@/components/client/ClientCombobox.vue';
import DealCombobox from '@/components/deal/common/DealCombobox.vue';
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
import {
    FormContent,
    FormControl,
    FormError,
    FormField,
    FormLabel,
    injectFormContext,
} from '@/components/ui/custom/form';
import { NumberInput, PriceInput, TextInput } from '@/components/ui/custom/input';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { CommercialDealFormData, useFormatter } from '@/composables';
import { DealScheduleStatus } from '@/types';
import { computed, ref, watch } from 'vue';

const { form } = injectFormContext<CommercialDealFormData>();
const defaultStatus: DealScheduleStatus = 'uncertain';
const newScheduleItem = ref({ date: '', amount: 0 });
const format = useFormatter();

function addMonths(date: Date, months: number): Date {
    const d = new Date(date);
    const day = d.getDate();
    d.setMonth(d.getMonth() + months);

    if (d.getDate() !== day) {
        return new Date(d.getFullYear(), d.getMonth() + 1, 0);
    }
    return d;
}

function generateSchedule() {
    if (!form.amount || !form.duration_in_months || !form.starts_at) return;

    const amount = form.amount;
    const duration = form.duration_in_months;
    const baseAmount = Math.floor(amount / duration);
    const remainder = amount - baseAmount * duration;

    let currentDate = new Date(form.starts_at);
    form.schedule_data = [];

    for (let i = 0; i < duration; i++) {
        const installmentAmount = i === 0 ? baseAmount + remainder : baseAmount;
        const dateStr = currentDate.toISOString().split('.')[0];

        form.schedule_data.push({
            date: dateStr,
            amount: installmentAmount,
            status: defaultStatus,
            title: '',
        });

        currentDate = addMonths(currentDate, 1);
    }
}

const targetedTurnover = computed(() => {
    if (!form.amount || !form.success_rate) return 0;
    return form.amount * (form.success_rate / 100);
});

watch(
    () => [form.amount, form.duration_in_months, form.starts_at],
    () => {
        if (form.amount && form.duration_in_months && form.starts_at) {
            generateSchedule();
        }
    },
    { deep: true },
);
</script>

<template>
    <FormContent class="sm:grid-cols-3">
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.commercial_deal.fields.name') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <TextInput v-model="form.name" />
            </FormControl>
            <FormError :message="form.errors.name" />
        </FormField>

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.commercial_deal.fields.amount') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <PriceInput v-model="form.amount" :min="0" />
            </FormControl>
            <FormError :message="form.errors.amount" />
        </FormField>

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.commercial_deal.fields.duration_in_months') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <NumberInput v-model="form.duration_in_months" />
            </FormControl>
            <FormError :message="form.errors.duration_in_months" />
        </FormField>

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.commercial_deal.fields.ordered_at') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <DatePicker v-model="form.ordered_at" />
            </FormControl>
            <FormError :message="form.errors.ordered_at" />
        </FormField>

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.commercial_deal.fields.starts_at') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <DatePicker v-model="form.starts_at" />
            </FormControl>
            <FormError :message="form.errors.starts_at" />
        </FormField>

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.commercial_deal.fields.success_rate') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <NumberInput v-model="form.success_rate" />
            </FormControl>
            <FormError :message="form.errors.success_rate" />
        </FormField>

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.commercial_deal.fields.code') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <TextInput v-model="form.code" />
            </FormControl>
            <FormError :message="form.errors.code" />
        </FormField>

        <FormField>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.commercial_deal.fields.deal_id') }}
                </CapitalizeText>
            </FormLabel>

            <DealCombobox v-model="form.parent" />
            <FormError :message="form.errors.deal_id" />
        </FormField>

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.commercial_deal.fields.client_id') }}
                </CapitalizeText>
            </FormLabel>

            <ClientCombobox v-model="form.client" />
            <FormError :message="form.errors.client_id" />
        </FormField>

        <FormField>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.commercial_deal.fields.targeted_turnover') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <TextInput
                    :model-value="format.price(targetedTurnover)"
                    readonly
                    class="bg-gray-100 dark:bg-gray-800"
                />
            </FormControl>
        </FormField>

        <div class="col-span-full mt-6">
            <h3 class="mb-4 text-lg font-medium">Échéancier</h3>

            <DataTable v-slot="{ rows }" :data="form.schedule_data">
                <DataTableContent tab="table">
                    <DataTableHeader>
                        <DataTableRow>
                            <DataTableHead>Date</DataTableHead>
                            <DataTableHead>Montant</DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow v-for="(item, index) in rows" :key="index" :item>
                            <DataTableCell>
                                {{ format.date(item.date) }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ format.price(item.amount) }}
                            </DataTableCell>
                        </DataTableRow>
                    </DataTableBody>
                </DataTableContent>
            </DataTable>

            <!-- TODO remove after -->
            <FormField>
                <FormError class="mb-4" :message="form.errors.schedule_data" />
            </FormField>
        </div>
    </FormContent>
</template>
