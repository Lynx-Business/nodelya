<script setup lang="ts">
import { CapitalizeText } from '@/components/ui/custom/typography';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useFormatter, useParser } from '@/composables';
import { AccountingPeriodResource } from '@/types';
import { computed } from 'vue';

type Props = {
    accountingPeriod: AccountingPeriodResource;
};
const props = defineProps<Props>();
const model = defineModel<string>();

const format = useFormatter();
const parse = useParser();

const months = computed(() =>
    (props.accountingPeriod.months ?? []).map((month) => format.timestamp(parse.toDate(month))),
);
</script>

<template>
    <Select v-model="model">
        <SelectTrigger class="w-auto">
            <SelectValue />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem v-for="month in months" :key="month" :value="month">
                    <CapitalizeText>
                        {{ format.date(month, { month: 'long', year: '2-digit' }) }}
                    </CapitalizeText>
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
