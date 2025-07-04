<script setup lang="ts">
import AccountingPeriodCombobox from '@/components/accounting-period/AccountingPeriodCombobox.vue';
import ProjectDepartmentCombobox from '@/components/project-department/ProjectDepartmentCombobox.vue';
import TrashedBadge from '@/components/trash/TrashedBadge.vue';
import TrashedFilterCombobox from '@/components/trash/TrashedFilterCombobox.vue';
import { Button } from '@/components/ui/button';
import {
    DataTable,
    DataTableBody,
    DataTableCell,
    DataTableContent,
    DataTableHead,
    DataTableHeadActions,
    DataTableHeader,
    DataTablePagination,
    DataTableRow,
    DataTableRowAction,
    DataTableRowActions,
    DataTableRowCheckbox,
    DataTableRowsAction,
    DataTableRowsCheckbox,
    DataTableSortableHead,
} from '@/components/ui/custom/data-table';
import { FiltersSheet, FiltersSheetContent, FiltersSheetTrigger } from '@/components/ui/custom/filters';
import { FormContent, FormControl, FormField, FormLabel } from '@/components/ui/custom/form';
import { TextInput } from '@/components/ui/custom/input';
import { InertiaLink } from '@/components/ui/custom/link';
import { Section, SectionContent } from '@/components/ui/custom/section';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { useAlert, useAuth, useFilters, useLayout } from '@/composables';
import { AppLayout } from '@/layouts';
import { ContractorIndexProps, ContractorIndexRequest, ContractorOneOrManyRequest, ContractorResource } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { reactiveOmit } from '@vueuse/core';
import { trans, transChoice } from 'laravel-vue-i18n';
import { ArchiveIcon, ArchiveRestoreIcon, CirclePlusIcon, EyeIcon, PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { ref } from 'vue';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.contractors.index.title'),
                href: route('contractors.index'),
            },
        ],
    })),
});

const props = defineProps<ContractorIndexProps>();

const { abilities } = useAuth();

const alert = useAlert();

const selectedRows = ref<ContractorResource[]>([]);
const rowsActions: DataTableRowsAction<ContractorResource>[] = [
    {
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (budgets) => budgets.some((contractor) => !contractor.can_trash),
        callback: (budgets) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.contractors.trash.confirm', budgets.length),
                callback: () =>
                    router.delete<ContractorOneOrManyRequest>(route('contractors.trash', {}), {
                        data: { ids: budgets.map(({ id }) => id) },
                        only: ['contractors'],
                        onSuccess: () => {
                            selectedRows.value = [];
                        },
                    }),
            }),
    },
    {
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (budgets) => budgets.some((contractor) => !contractor.can_restore),
        callback: (budgets) =>
            alert({
                variant: 'success',
                description: transChoice('messages.contractors.restore.confirm', budgets.length),
                callback: () =>
                    router.patch<ContractorOneOrManyRequest>(
                        route('contractors.restore', {}),
                        { ids: budgets.map(({ id }) => id) },
                        {
                            only: ['contractors'],
                            onSuccess: () => {
                                selectedRows.value = [];
                            },
                        },
                    ),
            }),
    },
    {
        label: trans('delete'),
        icon: Trash2Icon,
        disabled: (budgets) => budgets.some((contractor) => !contractor.can_delete),
        callback: (budgets) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.contractors.delete.confirm', budgets.length),
                callback: () =>
                    router.delete<ContractorOneOrManyRequest>(route('contractors.delete', {}), {
                        data: { ids: budgets.map(({ id }) => id) },
                        only: ['contractors'],
                        onSuccess: () => {
                            selectedRows.value = [];
                        },
                    }),
            }),
    },
];
const rowActions: DataTableRowAction<ContractorResource>[] = [
    {
        type: 'href',
        label: trans('view'),
        icon: EyeIcon,
        hidden: (contractor) => contractor.can_update || false,
        disabled: (contractor) => !contractor.can_view,
        href: (contractor) =>
            route('contractors.edit', {
                contractor,
            }),
    },
    {
        type: 'href',
        label: trans('edit'),
        icon: PencilIcon,
        disabled: (contractor) => !contractor.can_update,
        href: (contractor) =>
            route('contractors.edit', {
                contractor,
            }),
    },
    {
        type: 'callback',
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (contractor) => !contractor.can_trash,
        callback: (contractor) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.contractors.trash.confirm', 1),
                callback: () =>
                    router.delete<ContractorOneOrManyRequest>(
                        route('contractors.trash', {
                            contractor,
                        }),
                        {
                            only: ['contractors'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (contractor) => !contractor.can_restore,
        callback: (contractor) =>
            alert({
                variant: 'success',
                description: transChoice('messages.contractors.restore.confirm', 1),
                callback: () =>
                    router.patch<ContractorOneOrManyRequest>(
                        route('contractors.restore', {
                            contractor,
                        }),
                        undefined,
                        {
                            only: ['contractors'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('delete'),
        icon: Trash2Icon,
        disabled: (contractor) => !contractor.can_delete,
        callback: (contractor) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.contractors.delete.confirm', 1),
                callback: () =>
                    router.delete<ContractorOneOrManyRequest>(
                        route('contractors.delete', {
                            contractor,
                        }),
                        {
                            only: ['contractors'],
                        },
                    ),
            }),
    },
];

const filters = useFilters<ContractorIndexRequest>(
    route('contractors.index', {}),
    {
        q: props.request.q ?? '',
        page: props.request.page ?? props.contractors?.meta.current_page,
        per_page: props.request.per_page ?? props.contractors?.meta.per_page,
        sort_by: props.request.sort_by,
        sort_direction: props.request.sort_direction,
        trashed: props.request.trashed,
        accounting_period: props.request.accounting_period,
        project_departments: props.request.project_departments ?? [],
    },
    {
        only: ['contractors'],
        immediate: true,
        debounceReload(keys) {
            return !keys.includes('page') && !keys.includes('per_page');
        },
        onReload(keys) {
            if (!keys.includes('page')) {
                filters.page = 1;
            }
        },
        transform(data) {
            return {
                ...reactiveOmit(data, 'accounting_period', 'project_departments'),
                accounting_period_id: data.accounting_period?.id,
                project_department_ids: data.project_departments?.map(({ id }) => id),
            };
        },
    },
);
</script>

<template>
    <Head :title="$t('pages.contractors.index.title')" />

    <Section>
        <SectionContent>
            <DataTable
                v-slot="{ rows }"
                v-model:selected-rows="selectedRows"
                v-model:sort-by="filters.sort_by"
                v-model:sort-direction="filters.sort_direction"
                :data="contractors"
                :rows-actions
                :row-actions
            >
                <FormContent class="grid items-center sm:flex">
                    <TextInput v-model="filters.q" type="search" />
                    <AccountingPeriodCombobox v-model="filters.accounting_period" required />
                    <FiltersSheet
                        :filters
                        :omit="[
                            'q',
                            'page',
                            'per_page',
                            'sort_by',
                            'sort_direction',
                            'accounting_period_id',
                            'accounting_period',
                        ]"
                        :data="['trashedFilters', 'projectDepartments']"
                    >
                        <FiltersSheetTrigger />
                        <FiltersSheetContent>
                            <FormField>
                                <FormLabel>
                                    <CapitalizeText>
                                        {{ $t('trashed') }}
                                    </CapitalizeText>
                                </FormLabel>
                                <FormControl>
                                    <TrashedFilterCombobox v-model="filters.trashed" />
                                </FormControl>
                            </FormField>
                            <FormField>
                                <FormLabel>
                                    <CapitalizeText>
                                        {{ $t('models.project_department.name.many') }}
                                    </CapitalizeText>
                                </FormLabel>
                                <FormControl>
                                    <ProjectDepartmentCombobox v-model="filters.project_departments" multiple />
                                </FormControl>
                            </FormField>
                        </FiltersSheetContent>
                    </FiltersSheet>
                </FormContent>
                <FormContent class="flex items-center justify-between" v-if="abilities.contractors.create">
                    <Button class="ml-auto" as-child>
                        <InertiaLink :href="route('contractors.create')">
                            <CirclePlusIcon />
                            <CapitalizeText class="max-sm:hidden">
                                {{ $t('pages.contractors.create.title') }}
                            </CapitalizeText>
                        </InertiaLink>
                    </Button>
                </FormContent>
                <DataTableContent tab="table">
                    <DataTableHeader>
                        <DataTableRow>
                            <DataTableHead>
                                <DataTableRowsCheckbox />
                            </DataTableHead>
                            <DataTableSortableHead value="project_department_id">
                                {{ $t('models.project_department.name.one') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="full_name">
                                {{ $t('models.contractor.fields.full_name') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="email">
                                {{ $t('models.contractor.fields.email') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="phone">
                                {{ $t('models.contractor.fields.phone') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead v-if="filters.trashed" value="deleted_at">
                                {{ $t('models.contractor.fields.deleted_at') }}
                            </DataTableSortableHead>
                            <DataTableHead>
                                <DataTableHeadActions />
                            </DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow
                            v-for="contractor in rows"
                            :key="contractor.id"
                            :item="contractor"
                            :class="{ 'bg-destructive/5': contractor.deleted_at }"
                        >
                            <DataTableCell>
                                <DataTableRowCheckbox />
                            </DataTableCell>
                            <DataTableCell>
                                {{ contractor.project_department?.name }}
                            </DataTableCell>
                            <DataTableCell>
                                <CapitalizeText>
                                    {{ contractor.full_name }}
                                </CapitalizeText>
                            </DataTableCell>
                            <DataTableCell>
                                {{ contractor.email }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ contractor.phone }}
                            </DataTableCell>
                            <DataTableCell v-if="filters.trashed">
                                <TrashedBadge :item="contractor" />
                            </DataTableCell>
                            <DataTableCell>
                                <DataTableRowActions />
                            </DataTableCell>
                        </DataTableRow>
                    </DataTableBody>
                </DataTableContent>
                <DataTablePagination v-model:page="filters.page" v-model:per-page="filters.per_page" />
            </DataTable>
        </SectionContent>
    </Section>
</template>
