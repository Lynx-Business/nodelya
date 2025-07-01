<script setup lang="ts">
import ClientCombobox from '@/components/client/ClientCombobox.vue';
import DealCombobox from '@/components/deal/common/DealCombobox.vue';
import { Button } from '@/components/ui/button';
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
import { BillingDealFormData } from '@/composables';
import { PlusIcon, XIcon } from 'lucide-vue-next';
import { ref } from 'vue';

const { form } = injectFormContext<BillingDealFormData>();
const newScheduleItem = ref({ date: '', amount: 0 });

function addScheduleItem() {
    if (newScheduleItem.value.date && newScheduleItem.value.amount > 0) {
        form.schedule_data.push({
            ...newScheduleItem.value,
            status: 'pending',
            title: 'Échéance',
        });
        newScheduleItem.value = { date: '', amount: 0 };
    }
}

function removeScheduleItem(index: number) {
    form.schedule_data.splice(index, 1);
}
function getScheduleError(index: number, field: string) {
    const key = `schedule_data.${index}.${field}`;
    return (form.errors as Record<string, string>)[key] || '';
}
</script>

<template>
    <FormContent class="sm:grid-cols-3">
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.billing_deals.fields.name') }}
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
                    {{ $t('models.billing_deals.fields.amount') }}
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
                    {{ $t('models.billing_deals.fields.duration_in_months') }}
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
                    {{ $t('models.billing_deals.fields.ordered_at') }}
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
                    {{ $t('models.billing_deals.fields.starts_at') }}
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
                    {{ $t('models.billing_deals.fields.success_rate') }}
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
                    {{ $t('models.billing_deals.fields.code') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <TextInput v-model="form.code" />
            </FormControl>
            <FormError :message="form.errors.code" />
        </FormField>

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.billing_deals.fields.client_id') }}
                </CapitalizeText>
            </FormLabel>

            <ClientCombobox v-model="form.client" />
            <FormError :message="form.errors.client_id" />
        </FormField>

        <FormField>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.billing_deals.fields.deal_id') }}
                </CapitalizeText>
            </FormLabel>

            <DealCombobox v-model="form.parent" />
            <FormError :message="form.errors.deal_id" />
        </FormField>

        <div class="col-span-full mt-6">
            <h3 class="mb-4 text-lg font-medium">Échéancier</h3>
            <FormField>
                <FormError class="mb-4" :message="form.errors.schedule_data" />
            </FormField>

            <div class="mb-4 flex items-center gap-2">
                <FormField>
                    <FormControl>
                        <DatePicker v-model="newScheduleItem.date" />
                    </FormControl>
                </FormField>
                <FormField>
                    <FormControl>
                        <PriceInput v-model="newScheduleItem.amount" />
                    </FormControl>
                </FormField>
                <Button @click="addScheduleItem" variant="ghost" size="icon">
                    <PlusIcon class="cursor-pointer" />
                </Button>
            </div>

            <div class="overflow-hidden rounded-lg border">
                <div
                    class="flex items-center gap-2 border-b p-3"
                    v-for="(item, index) in form.schedule_data"
                    :key="index"
                >
                    <FormField required>
                        <FormControl>
                            <DatePicker v-model="item.date" />
                        </FormControl>
                        <FormError :message="getScheduleError(index, 'date')" />
                    </FormField>
                    <FormField required>
                        <FormControl>
                            <PriceInput v-model="item.amount" />
                        </FormControl>
                        <FormError class="mt-1" :message="getScheduleError(index, 'amount')" />
                    </FormField>

                    <Button @click="removeScheduleItem(index)" variant="ghost" size="icon">
                        <XIcon class="bg-red text-red-500" />
                    </Button>
                </div>
            </div>
        </div>
    </FormContent>
</template>
