<script setup lang="ts">
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
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { CommercialDealFormData, usePageProp } from '@/composables';
import { ClientListResource } from '@/types';
import { WhenVisible } from '@inertiajs/vue3';
import { ref } from 'vue';

const clients = usePageProp<ClientListResource[]>('clients', []);
console.log('clients', clients);

const { form } = injectFormContext<CommercialDealFormData>();
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
    <FormContent class="sm:grid-cols-2">
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

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.commercial_deal.fields.reference') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <TextInput v-model="form.reference" />
            </FormControl>
            <FormError :message="form.errors.reference" />
        </FormField>

        <WhenVisible data="clients">
            <FormField required>
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('models.commercial_deal.fields.client_id') }}
                    </CapitalizeText>
                </FormLabel>
                <Select v-model="form.client_id">
                    <SelectTrigger class="w-full">
                        <SelectValue>
                            <template v-if="form.client_id">
                                {{ clients.find((c) => c.id === form.client_id)?.name || '' }}
                            </template>
                        </SelectValue>
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="client in clients" :key="client.id" :value="client.id">
                            {{ client.name }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <FormError :message="form.errors.client_id" />
            </FormField>
        </WhenVisible>

        <div class="col-span-full mt-6">
            <h3 class="mb-4 text-lg font-medium">Échéancier</h3>
            <FormField>
                <FormError class="mb-4" :message="form.errors.schedule_data" />
            </FormField>

            <div class="mb-4 flex gap-2">
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
                <Button @click="addScheduleItem"> Ajouter </Button>
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
                    <Button variant="destructive" @click="removeScheduleItem(index)"> Retirer </Button>
                </div>
            </div>
        </div>
    </FormContent>
</template>
