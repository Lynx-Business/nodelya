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
import { EmployeeIndexProps, EmployeeIndexRequest, EmployeeOneOrManyRequest, EmployeeResource } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { reactiveOmit } from '@vueuse/core';
import { trans, transChoice } from 'laravel-vue-i18n';
import { ArchiveIcon, ArchiveRestoreIcon, CirclePlusIcon, EyeIcon, PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { ref } from 'vue';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.employees.index.title'),
                href: route('employees.index'),
            },
        ],
    })),
});

const props = defineProps<EmployeeIndexProps>();

const { abilities } = useAuth();

const alert = useAlert();

const selectedRows = ref<EmployeeResource[]>([]);
const rowsActions: DataTableRowsAction<EmployeeResource>[] = [
    {
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (budgets) => budgets.some((employee) => !employee.can_trash),
        callback: (budgets) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.employees.trash.confirm', budgets.length),
                callback: () =>
                    router.delete<EmployeeOneOrManyRequest>(route('employees.trash', {}), {
                        data: { ids: budgets.map(({ id }) => id) },
                        only: ['employees'],
                        onSuccess: () => {
                            selectedRows.value = [];
                        },
                    }),
            }),
    },
    {
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (budgets) => budgets.some((employee) => !employee.can_restore),
        callback: (budgets) =>
            alert({
                variant: 'success',
                description: transChoice('messages.employees.restore.confirm', budgets.length),
                callback: () =>
                    router.patch<EmployeeOneOrManyRequest>(
                        route('employees.restore', {}),
                        { ids: budgets.map(({ id }) => id) },
                        {
                            only: ['employees'],
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
        disabled: (budgets) => budgets.some((employee) => !employee.can_delete),
        callback: (budgets) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.employees.delete.confirm', budgets.length),
                callback: () =>
                    router.delete<EmployeeOneOrManyRequest>(route('employees.delete', {}), {
                        data: { ids: budgets.map(({ id }) => id) },
                        only: ['employees'],
                        onSuccess: () => {
                            selectedRows.value = [];
                        },
                    }),
            }),
    },
];
const rowActions: DataTableRowAction<EmployeeResource>[] = [
    {
        type: 'href',
        label: trans('view'),
        icon: EyeIcon,
        hidden: (employee) => employee.can_update || false,
        disabled: (employee) => !employee.can_view,
        href: (employee) =>
            route('employees.edit', {
                employee,
            }),
    },
    {
        type: 'href',
        label: trans('edit'),
        icon: PencilIcon,
        disabled: (employee) => !employee.can_update,
        href: (employee) =>
            route('employees.edit', {
                employee,
            }),
    },
    {
        type: 'callback',
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (employee) => !employee.can_trash,
        callback: (employee) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.employees.trash.confirm', 1),
                callback: () =>
                    router.delete<EmployeeOneOrManyRequest>(
                        route('employees.trash', {
                            employee,
                        }),
                        {
                            only: ['employees'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (employee) => !employee.can_restore,
        callback: (employee) =>
            alert({
                variant: 'success',
                description: transChoice('messages.employees.restore.confirm', 1),
                callback: () =>
                    router.patch<EmployeeOneOrManyRequest>(
                        route('employees.restore', {
                            employee,
                        }),
                        undefined,
                        {
                            only: ['employees'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('delete'),
        icon: Trash2Icon,
        disabled: (employee) => !employee.can_delete,
        callback: (employee) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.employees.delete.confirm', 1),
                callback: () =>
                    router.delete<EmployeeOneOrManyRequest>(
                        route('employees.delete', {
                            employee,
                        }),
                        {
                            only: ['employees'],
                        },
                    ),
            }),
    },
];

const filters = useFilters<EmployeeIndexRequest>(
    route('employees.index', {}),
    {
        q: props.request.q ?? '',
        page: props.request.page ?? props.employees?.meta.current_page,
        per_page: props.request.per_page ?? props.employees?.meta.per_page,
        sort_by: props.request.sort_by,
        sort_direction: props.request.sort_direction,
        trashed: props.request.trashed,
        accounting_period: props.request.accounting_period,
        project_departments: props.request.project_departments ?? [],
    },
    {
        only: ['employees'],
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
    <Head :title="$t('pages.employees.index.title')" />

    <Section>
        <SectionContent>
            <DataTable
                v-slot="{ rows }"
                v-model:selected-rows="selectedRows"
                v-model:sort-by="filters.sort_by"
                v-model:sort-direction="filters.sort_direction"
                :data="employees"
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
                <FormContent class="flex items-center justify-between" v-if="abilities.employees.create">
                    <Button class="ml-auto" as-child>
                        <InertiaLink :href="route('employees.create')">
                            <CirclePlusIcon />
                            <CapitalizeText class="max-sm:hidden">
                                {{ $t('pages.employees.create.title') }}
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
                                {{ $t('models.employee.fields.full_name') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="email">
                                {{ $t('models.employee.fields.email') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead value="phone">
                                {{ $t('models.employee.fields.phone') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead v-if="filters.trashed" value="deleted_at">
                                {{ $t('models.employee.fields.deleted_at') }}
                            </DataTableSortableHead>
                            <DataTableHead>
                                <DataTableHeadActions />
                            </DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow
                            v-for="employee in rows"
                            :key="employee.id"
                            :item="employee"
                            :class="{ 'bg-destructive/5': employee.deleted_at }"
                        >
                            <DataTableCell>
                                <DataTableRowCheckbox />
                            </DataTableCell>
                            <DataTableCell>
                                {{ employee.project_department?.name }}
                            </DataTableCell>
                            <DataTableCell>
                                <CapitalizeText>
                                    {{ employee.full_name }}
                                </CapitalizeText>
                            </DataTableCell>
                            <DataTableCell>
                                {{ employee.email }}
                            </DataTableCell>
                            <DataTableCell>
                                {{ employee.phone }}
                            </DataTableCell>
                            <DataTableCell v-if="filters.trashed">
                                <TrashedBadge :item="employee" />
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
