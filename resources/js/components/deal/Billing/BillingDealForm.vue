<script setup lang="ts">
import ClientCombobox from '@/components/client/ClientCombobox.vue';
import DealCombobox from '@/components/deal/common/DealCombobox.vue';
import ProjectDepartmentCombobox from '@/components/project-department/ProjectDepartmentCombobox.vue';
import { Button } from '@/components/ui/button';
import { EnumCombobox } from '@/components/ui/custom/combobox';
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
import { NumberInput, PriceInput, TextInput } from '@/components/ui/custom/input';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { BillingDealFormData, useFormatter } from '@/composables';
import { DealScheduleStatus, ScheduleItemData } from '@/types';
import { trans } from 'laravel-vue-i18n';
import { ClockArrowUpIcon, PlusIcon, Trash2Icon } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import ExpenseChargeTable from '../commercial/ExpenseChargeTable.vue';
import PostponeScheduleDialog from './PostponeScheduleDialog.vue';

const { form, disabled } = injectFormContext<BillingDealFormData>();
const defaultStatus: DealScheduleStatus = 'uncertain';
const paidStatus: DealScheduleStatus = 'paid';
const newScheduleItem = ref({ date: '', amount: 0, title: '' });
const format = useFormatter();

const showPostponeDialog = ref(false);
const postponeMonths = ref(1);
const selectedItemIndex = ref<number | null>(null);

function openPostponeDialog(item: ScheduleItemData) {
    const index = form.schedule_data.findIndex((i) => i === item);
    if (index !== -1) {
        selectedItemIndex.value = index;
        showPostponeDialog.value = true;
    }
}

function postponeSchedules() {
    if (selectedItemIndex.value === null) return;

    const selectedItem = form.schedule_data[selectedItemIndex.value];
    const selectedDate = selectedItem.date;
    const months = postponeMonths.value;

    const updatedSchedule = form.schedule_data.map((item) => {
        if (new Date(item.date) < new Date(selectedDate) || item.status === paidStatus) {
            return item;
        }

        return {
            ...item,
            date: addMonthsToDate(item.date, months),
        };
    });

    form.schedule_data = updatedSchedule;
    showPostponeDialog.value = false;
    postponeMonths.value = 1;
}

function addMonthsToDate(dateString: string, months: number): string {
    const date = new Date(dateString);
    date.setMonth(date.getMonth() + months);

    return new Date(date.getFullYear(), date.getMonth(), date.getDate()).toISOString().slice(0, 19);
}

const totalScheduleAmount = computed(() => {
    return (
        form.schedule_data.reduce((sum: any, item: any) => sum + (item.amount || 0), 0) + newScheduleItem.value.amount
    );
});

const scheduleTotalError = computed(() => {
    if (totalScheduleAmount.value !== (form?.amount ?? 0)) {
        return trans('validation.custom.schedule_total_exceeds', {
            total: format.price(totalScheduleAmount.value),
            principal: format.price(form?.amount ?? 0),
        });
    }
    return null;
});

function addScheduleItem() {
    if (totalScheduleAmount.value === (form?.amount ?? 0)) {
        return;
    }
    if (newScheduleItem.value.date && newScheduleItem.value.amount > 0) {
        form.schedule_data.push({
            ...newScheduleItem.value,
            status: defaultStatus,
        });
        form.schedule_data.sort((a, b) => new Date(a.date).getTime() - new Date(b.date).getTime());
        newScheduleItem.value = { date: '', amount: 0, title: '' };
    }
}

function removeScheduleItem(item: ScheduleItemData) {
    const index = form.schedule_data.findIndex(
        (i: ScheduleItemData) =>
            i.date === item.date && i.amount === item.amount && i.status === item.status && i.title === item.title,
    );
    if (index !== -1) {
        form.schedule_data.splice(index, 1);
    }
}

function getScheduleError(index: number, field: string) {
    const key = `schedule_data.${index}.${field}`;
    return (form.errors as Record<string, string>)[key] || '';
}

const rowActions: DataTableRowCallbackAction<ScheduleItemData>[] = [
    {
        type: 'callback',
        label: trans('messages.deals.billings.postpone_upcoming_invoices'),
        icon: ClockArrowUpIcon,
        disabled: (item) => false,
        callback: (item) => openPostponeDialog(item),
    },
    {
        type: 'callback',
        label: trans('delete'),
        icon: Trash2Icon,
        disabled: (item) => false,
        callback: (item) => removeScheduleItem(item),
    },
];
</script>

<template>
    <FormContent class="sm:grid-cols-3">
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.deal.billing.fields.name') }}
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
                    {{ $t('models.deal.billing.fields.amount') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <PriceInput v-model="form.amount" :min="0" disabled />
            </FormControl>
            <FormError :message="form.errors.amount" />
        </FormField>

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.deal.billing.fields.duration_in_months') }}
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
                    {{ $t('models.deal.billing.fields.ordered_at') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <DatePicker v-model="form.ordered_at" disabled />
            </FormControl>
            <FormError :message="form.errors.ordered_at" />
        </FormField>

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.deal.billing.fields.starts_at') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <DatePicker v-model="form.starts_at" disabled />
            </FormControl>
            <FormError :message="form.errors.starts_at" />
        </FormField>

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.deal.billing.fields.code') }}
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
                    {{ $t('models.deal.billing.fields.reference') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <TextInput :model-value="form.reference" disabled />
            </FormControl>
            <FormError :message="form.errors.reference" />
        </FormField>

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.deal.billing.fields.client_id') }}
                </CapitalizeText>
            </FormLabel>

            <ClientCombobox v-model="form.client" />
            <FormError :message="form.errors.client" />
        </FormField>

        <FormField>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.project_department.name.one') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <ProjectDepartmentCombobox v-model="form.project_department" />
            </FormControl>
            <FormError :message="form.errors.project_department" />
        </FormField>

        <FormField>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.deal.billing.fields.deal_id') }}
                </CapitalizeText>
            </FormLabel>

            <DealCombobox v-model="form.parent" />
            <FormError :message="form.errors.parent" />
        </FormField>

        <div class="col-span-full mt-2">
            <h4 class="mb-4 text-lg font-medium">{{ $t('pages.deals.billings.schedule') }}</h4>

            <div class="mb-4 flex items-center gap-2" v-if="disabled">
                <FormField>
                    <FormLabel>
                        <CapitalizeText> {{ $t('models.deal.billing.fields.schedule_data.date') }} </CapitalizeText>
                    </FormLabel>
                    <FormControl>
                        <DatePicker v-model="newScheduleItem.date" :min-value="form?.starts_at" />
                    </FormControl>
                </FormField>
                <FormField>
                    <FormLabel>
                        <CapitalizeText>
                            {{ $t('models.deal.billing.fields.amount') }}
                        </CapitalizeText>
                    </FormLabel>
                    <FormControl>
                        <PriceInput v-model="newScheduleItem.amount" />
                    </FormControl>
                </FormField>
                <FormField>
                    <FormLabel>
                        <CapitalizeText> {{ $t('models.deal.billing.fields.schedule_data.title') }} </CapitalizeText>
                    </FormLabel>
                    <FormControl>
                        <TextInput v-model="newScheduleItem.title" />
                    </FormControl>
                </FormField>
                <div class="flex items-center">
                    <Button @click="addScheduleItem" variant="ghost" size="icon" class="mt-4">
                        <PlusIcon />
                    </Button>
                </div>
            </div>

            <DataTable v-slot="{ rows }" :data="form.schedule_data" :row-actions="rowActions">
                <DataTableContent tab="table">
                    <DataTableHeader>
                        <DataTableRow>
                            <DataTableHead>{{ $t('models.deal.billing.fields.schedule_data.date') }}</DataTableHead>
                            <DataTableHead>{{ $t('models.deal.billing.fields.schedule_data.amount') }}</DataTableHead>
                            <DataTableHead>{{ $t('models.deal.billing.fields.schedule_data.status') }}</DataTableHead>
                            <DataTableHead>{{ $t('models.deal.billing.fields.schedule_data.title') }}</DataTableHead>
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
                                <FormField required>
                                    <FormControl>
                                        <EnumCombobox
                                            v-model="item.status"
                                            data="schedule_status"
                                            placeholder="$t('pages.deals.billings.schedule_status_placeholder')"
                                        />
                                    </FormControl>
                                    <FormError :message="getScheduleError(index, 'status')" />
                                </FormField>
                            </DataTableCell>
                            <DataTableCell>
                                <FormField required>
                                    <FormControl>
                                        <TextInput v-model="item.title" />
                                    </FormControl>
                                    <FormError :message="getScheduleError(index, 'title')" />
                                </FormField>
                            </DataTableCell>
                            <DataTableCell>
                                <DataTableRowActions />
                            </DataTableCell>
                        </DataTableRow>
                    </DataTableBody>
                </DataTableContent>
            </DataTable>

            <div class="mt-4 flex items-center justify-between">
                <span
                    >{{ $t('pages.deals.billings.schedule_totals.total') }}:
                    {{ format.price(totalScheduleAmount) }}</span
                >
                <span
                    >{{ $t('pages.deals.billings.schedule_totals.principal') }}:
                    {{ format.price(form?.amount ?? 0) }}</span
                >
            </div>

            <div v-if="scheduleTotalError" class="mt-4 text-sm text-red-500">
                {{ scheduleTotalError }}
            </div>
            <FormField v-else>
                <FormError class="mt-4" :message="form.errors.schedule_data" />
            </FormField>
        </div>

        <FormField class="col-span-full mt-4">
            <ExpenseChargeTable v-model:charges="form.expense_charges" v-model:errors="form.errors" />
        </FormField>

        <PostponeScheduleDialog
            v-model:showPostponeDialog="showPostponeDialog"
            v-model:postponeMonths="postponeMonths"
            v-model:postponeSchedules="postponeSchedules"
        />
    </FormContent>
</template>
