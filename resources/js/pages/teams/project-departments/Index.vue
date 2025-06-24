<script setup lang="ts">
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
import { useAlert, useFilters, useLayout } from '@/composables';
import { TeamsFormLayout } from '@/layouts';
import {
    ProjectDepartmentIndexProps,
    ProjectDepartmentIndexRequest,
    ProjectDepartmentIndexResource,
    ProjectDepartmentOneOrManyRequest,
} from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { trans, transChoice } from 'laravel-vue-i18n';
import { ArchiveIcon, ArchiveRestoreIcon, CirclePlusIcon, EyeIcon, PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { ref } from 'vue';

defineOptions({
    layout: useLayout(TeamsFormLayout, () => ({})),
});

const props = defineProps<ProjectDepartmentIndexProps>();

const alert = useAlert();

const selectedRows = ref<ProjectDepartmentIndexResource[]>([]);
const rowsActions: DataTableRowsAction<ProjectDepartmentIndexResource>[] = [
    {
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (items) => items.some((projectDepartment) => !projectDepartment.can_trash),
        callback: (items) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.project_departments.trash.confirm', items.length),
                callback: () =>
                    router.delete<ProjectDepartmentOneOrManyRequest>(
                        route('teams.project-departments.trash', { team: props.team }),
                        {
                            data: { ids: items.map(({ id }) => id) },
                            only: ['projectDepartments'],
                            onSuccess: () => {
                                selectedRows.value = [];
                            },
                        },
                    ),
            }),
    },
    {
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (items) => items.some((projectDepartment) => !projectDepartment.can_restore),
        callback: (items) =>
            alert({
                variant: 'success',
                description: transChoice('messages.project_departments.restore.confirm', items.length),
                callback: () =>
                    router.patch<ProjectDepartmentOneOrManyRequest>(
                        route('teams.project-departments.restore', { team: props.team }),
                        { ids: items.map(({ id }) => id) },
                        {
                            only: ['projectDepartments'],
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
        disabled: (items) => items.some((projectDepartment) => !projectDepartment.can_delete),
        callback: (items) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.project_departments.delete.confirm', items.length),
                callback: () =>
                    router.delete<ProjectDepartmentOneOrManyRequest>(
                        route('teams.project-departments.delete', { team: props.team }),
                        {
                            data: { ids: items.map(({ id }) => id) },
                            only: ['projectDepartments'],
                            onSuccess: () => {
                                selectedRows.value = [];
                            },
                        },
                    ),
            }),
    },
];
const rowActions: DataTableRowAction<ProjectDepartmentIndexResource>[] = [
    {
        type: 'href',
        label: trans('view'),
        icon: EyeIcon,
        hidden: (projectDepartment) => projectDepartment.can_update,
        disabled: (projectDepartment) => !projectDepartment.can_view,
        href: (projectDepartment) => route('teams.project-departments.edit', { team: props.team, projectDepartment }),
    },
    {
        type: 'href',
        label: trans('edit'),
        icon: PencilIcon,
        disabled: (projectDepartment) => !projectDepartment.can_update,
        href: (projectDepartment) => route('teams.project-departments.edit', { team: props.team, projectDepartment }),
    },
    {
        type: 'callback',
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (projectDepartment) => !projectDepartment.can_trash,
        callback: (projectDepartment) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.project_departments.trash.confirm', 1),
                callback: () =>
                    router.delete<ProjectDepartmentOneOrManyRequest>(
                        route('teams.project-departments.trash', { team: props.team, projectDepartment }),
                        {
                            only: ['projectDepartments'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (projectDepartment) => !projectDepartment.can_restore,
        callback: (projectDepartment) =>
            alert({
                variant: 'success',
                description: transChoice('messages.project_departments.restore.confirm', 1),
                callback: () =>
                    router.patch<ProjectDepartmentOneOrManyRequest>(
                        route('teams.project-departments.restore', { team: props.team, projectDepartment }),
                        undefined,
                        {
                            only: ['projectDepartments'],
                        },
                    ),
            }),
    },
    {
        type: 'callback',
        label: trans('delete'),
        icon: Trash2Icon,
        disabled: (projectDepartment) => !projectDepartment.can_delete,
        callback: (projectDepartment) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.project_departments.delete.confirm', 1),
                callback: () =>
                    router.delete<ProjectDepartmentOneOrManyRequest>(
                        route('teams.project-departments.delete', { team: props.team, projectDepartment }),
                        {
                            only: ['projectDepartments'],
                        },
                    ),
            }),
    },
];

const filters = useFilters<ProjectDepartmentIndexRequest>(
    route('teams.project-departments.index', { team: props.team }),
    {
        q: props.request.q ?? '',
        page: props.request.page ?? props.projectDepartments?.meta.current_page,
        per_page: props.request.per_page ?? props.projectDepartments?.meta.per_page,
        sort_by: props.request.sort_by,
        sort_direction: props.request.sort_direction,
        trashed: props.request.trashed,
    },
    {
        only: ['projectDepartments'],
        immediate: true,
        debounceReload(keys) {
            return !keys.includes('page') && !keys.includes('per_page');
        },
        onReload(keys) {
            if (!keys.includes('page')) {
                filters.page = 1;
            }
        },
    },
);
</script>

<template>
    <Head :title="$t('pages.teams.project_departments.index.title')" />

    <Section class="w-full p-0!">
        <SectionContent class="px-0!">
            <DataTable
                v-slot="{ rows }"
                v-model:selected-rows="selectedRows"
                v-model:sort-by="filters.sort_by"
                v-model:sort-direction="filters.sort_direction"
                :data="projectDepartments"
                :rows-actions
                :row-actions
            >
                <FormContent class="flex items-center">
                    <TextInput v-model="filters.q" type="search" />
                    <FiltersSheet
                        :filters
                        :omit="['q', 'page', 'per_page', 'sort_by', 'sort_direction']"
                        :data="['trashedFilters']"
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
                        </FiltersSheetContent>
                    </FiltersSheet>
                </FormContent>
                <FormContent class="flex items-center justify-between">
                    <Button class="ml-auto" as-child>
                        <InertiaLink :href="route('teams.project-departments.create', { team })">
                            <CirclePlusIcon />
                            <CapitalizeText class="max-sm:hidden">
                                {{ $t('pages.teams.project_departments.create.title') }}
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
                            <DataTableSortableHead value="name">
                                {{ $t('models.project_department.fields.name') }}
                            </DataTableSortableHead>
                            <DataTableSortableHead v-if="filters.trashed" value="deleted_at">
                                {{ $t('models.project_department.fields.deleted_at') }}
                            </DataTableSortableHead>
                            <DataTableHead>
                                <DataTableHeadActions />
                            </DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow
                            v-for="projectDepartment in rows"
                            :key="projectDepartment.id"
                            :item="projectDepartment"
                            :class="{ 'bg-destructive/5': projectDepartment.deleted_at }"
                        >
                            <DataTableCell>
                                <DataTableRowCheckbox />
                            </DataTableCell>
                            <DataTableCell>
                                {{ projectDepartment.name }}
                            </DataTableCell>
                            <DataTableCell v-if="filters.trashed">
                                <TrashedBadge :item="projectDepartment" />
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
