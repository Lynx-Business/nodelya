<script setup lang="ts">
import { DatePicker } from '@/components/ui/custom/date-picker';
import {
    FormContent,
    FormControl,
    FormError,
    FormField,
    FormLabel,
    injectFormContext,
} from '@/components/ui/custom/form';
import { TextInput } from '@/components/ui/custom/input';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { DealFormData } from '@/composables';
import { ref } from 'vue';

const { form } = injectFormContext<DealFormData>();
const newScheduleItem = ref({ date: '', amount: 0 });

function addScheduleItem() {
    if (newScheduleItem.value.date && newScheduleItem.value.amount > 0) {
        form.schedule.push({
            ...newScheduleItem.value,
            status: 'pending',
            title: 'Échéance',
        });
        newScheduleItem.value = { date: '', amount: 0 };
    }
}

function removeScheduleItem(index: number) {
    form.schedule.splice(index, 1);
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
                <TextInput v-model="form.amount" />
            </FormControl>
            <FormError :message="form.errors.amount" />
        </FormField>

        <FormField>
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

        <!-- Autres champs... -->

        <div class="col-span-full mt-6">
            <h3 class="mb-4 text-lg font-medium">Échéancier</h3>

            <div class="mb-4 flex gap-2">
                <DatePicker v-model="newScheduleItem.date" />
                <TextInput v-model="newScheduleItem.amount" placeholder="Montant" />
                <button class="rounded bg-blue-500 px-4 py-2 text-white" type="button" @click="addScheduleItem">
                    Ajouter
                </button>
            </div>

            <div class="overflow-hidden rounded-lg border">
                <div class="flex items-center border-b p-3" v-for="(item, index) in form.schedule" :key="index">
                    <div class="flex-1">
                        <DatePicker v-model="item.date" />
                    </div>
                    <div class="ml-2 flex-1">
                        <TextInput v-model="item.amount" />
                    </div>
                    <button
                        class="ml-4 text-red-500 hover:text-red-700"
                        type="button"
                        @click="removeScheduleItem(index)"
                    >
                        Retirer
                    </button>
                </div>
            </div>
        </div>
    </FormContent>
</template>
