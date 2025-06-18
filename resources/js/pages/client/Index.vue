<script setup lang="ts">
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
    DataTablePagination,
    DataTableRow,
    DataTableRowAction,
    DataTableRowActions,
    DataTableRowCheckbox,
    DataTableRowsAction,
    DataTableRowsActions,
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
import { AppLayout } from '@/layouts';
import type { ClientIndexProps, ClientIndexRequest, ClientIndexResource, ClientOneOrManyRequest } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { trans, transChoice } from 'laravel-vue-i18n';
import { ArchiveIcon, ArchiveRestoreIcon, CirclePlusIcon, EyeIcon, PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { ref } from 'vue';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.clients.index.title'),
                href: route('index'),
            },
        ],
    })),
});

const props = defineProps<ClientIndexProps>();

const alert = useAlert();

const selectedRows = ref<ClientIndexResource[]>([]);
const rowsActions: DataTableRowsAction<ClientIndexResource>[] = [
    {
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (items) => items.some((client) => !client.can_trash),
        callback: (items) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.clients.trash.confirm', items.length),
                callback: () =>
                    router.delete<ClientOneOrManyRequest>(route('clients.trash'), {
                        data: { ids: items.map(({ id }) => id) },
                        only: ['clients'],
                        onSuccess: () => {
                            selectedRows.value = [];
                        },
                    }),
            }),
    },
    {
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (items) => items.some((client) => !client.can_restore),
        callback: (items) =>
            alert({
                variant: 'success',
                description: transChoice('messages.clients.restore.confirm', items.length),
                callback: () =>
                    router.patch<ClientOneOrManyRequest>(
                        route('clients.restore'),
                        { ids: items.map(({ id }) => id) },
                        {
                            only: ['clients'],
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
        disabled: (items) => items.some((client) => !client.can_delete),
        callback: (items) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.clients.delete.confirm', items.length),
                callback: () =>
                    router.delete<ClientOneOrManyRequest>(route('clients.delete'), {
                        data: { ids: items.map(({ id }) => id) },
                        only: ['clients'],
                        onSuccess: () => {
                            selectedRows.value = [];
                        },
                    }),
            }),
    },
];
const rowActions: DataTableRowAction<ClientIndexResource>[] = [
    {
        type: 'href',
        label: trans('edit'),
        icon: PencilIcon,
        href: (client) => route('clients.edit', { client }),
    },
    {
        type: 'href',
        label: trans('view'),
        icon: EyeIcon,
        hidden: (user) => user.can_update,
        disabled: (user) => !user.can_view,
        href: (client) => route('clients.edit', { client }),
    },
    {
        type: 'callback',
        label: trans('trash'),
        icon: ArchiveIcon,
        disabled: (client) => !client.can_trash,
        callback: (client) =>
            alert({
                variant: 'warning',
                description: transChoice('messages.clients.trash.confirm', 1),
                callback: () =>
                    router.delete<ClientOneOrManyRequest>(route('clients.trash', { client }), {
                        only: ['clients'],
                    }),
            }),
    },
    {
        type: 'callback',
        label: trans('restore'),
        icon: ArchiveRestoreIcon,
        disabled: (client) => !client.can_restore,
        callback: (client) =>
            alert({
                variant: 'success',
                description: transChoice('messages.clients.restore.confirm', 1),
                callback: () =>
                    router.patch<ClientOneOrManyRequest>(route('clients.restore', { client }), undefined, {
                        only: ['clients'],
                    }),
            }),
    },
    {
        type: 'callback',
        label: trans('delete'),
        icon: Trash2Icon,
        disabled: (client) => !client.can_delete,
        callback: (client) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.clients.delete.confirm', 1),
                callback: () =>
                    router.delete<ClientOneOrManyRequest>(route('clients.delete', { client }), {
                        only: ['clients'],
                    }),
            }),
    },
];

const filters = useFilters<ClientIndexRequest>(
    route('clients.index'),
    {
        q: route().params.q ?? '',
        page: props.clients?.meta.current_page,
        per_page: props.clients?.meta.per_page,
        sort_by: props.request.sort_by,
        sort_direction: props.request.sort_direction,
        trashed: props.request.trashed,
    },
    {
        only: ['clients'],
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
    <Head :title="trans('pages.clients.title')" />

    <Section>
        <SectionContent>
            <DataTable
                v-slot="{ rows }"
                v-model:selected-rows="selectedRows"
                v-model:sort-by="filters.sort_by"
                v-model:sort-direction="filters.sort_direction"
                :data="clients"
                :rows-actions
                :row-actions
            >
                <FormContent class="flex items-center">
                    <TextInput v-model="filters.q" type="search" />
                    <FiltersSheet
                        :filters
                        :omit="['q', 'page', 'per_page', 'sort_by', 'sort_direction']"
                        :data="['trashed_filters']"
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
                                    <EnumCombobox v-model="filters.trashed" data="trashed_filters" />
                                </FormControl>
                            </FormField>
                        </FiltersSheetContent>
                    </FiltersSheet>
                </FormContent>
                <FormContent class="flex items-center justify-between">
                    <DataTableRowsActions />
                    <Button as-child>
                        <InertiaLink :href="route('clients.create')">
                            <CirclePlusIcon />
                            <CapitalizeText>
                                {{ $t('pages.clients.create.title') }}
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
                                {{ $t('models.client.fields.name') }}
                            </DataTableSortableHead>
                            <DataTableHead>
                                <DataTableHeadActions />
                            </DataTableHead>
                        </DataTableRow>
                    </DataTableHeader>
                    <DataTableBody>
                        <DataTableRow v-for="client in rows" :key="client.id" :item="client">
                            <DataTableCell>
                                <DataTableRowCheckbox />
                            </DataTableCell>
                            <DataTableCell>
                                <CapitalizeText>
                                    {{ client.name }}
                                </CapitalizeText>
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
