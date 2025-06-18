<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
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
import type { BannerOneOrManyRequest, ClientIndexProps, ClientIndexRequest, ClientIndexResource } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { trans, transChoice } from 'laravel-vue-i18n';
import { CirclePlusIcon, PencilIcon, ToggleRightIcon, Trash2Icon } from 'lucide-vue-next';
import { CheckboxCheckedState } from 'reka-ui';
import { computed, ref } from 'vue';

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
        label: trans('enable'),
        icon: ToggleRightIcon,
        callback: (items) => {
            console.log('items => ', items);
        },
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
        type: 'callback',
        label: trans('delete'),
        icon: Trash2Icon,
        callback: (banner) =>
            alert({
                variant: 'destructive',
                description: transChoice('messages.banners.delete.confirm', 1),
                callback: () =>
                    router.delete<BannerOneOrManyRequest>(route('admin.banners.delete', { banner }), {
                        only: ['banners'],
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
        sort_by: route().params.sort_by,
        sort_direction: route().params.sort_direction,
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

const isEnabledFilter = computed<CheckboxCheckedState>({
    get: () => filters.is_enabled ?? 'indeterminate',
    set: (value) => {
        filters.is_enabled = value === 'indeterminate' ? undefined : value;
    },
});
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
                    <FiltersSheet :filters :omit="['q', 'page', 'per_page', 'sort_by', 'sort_direction']">
                        <FiltersSheetTrigger />
                        <FiltersSheetContent>
                            <FormField>
                                <FormLabel>
                                    <FormControl>
                                        <Checkbox v-model="isEnabledFilter" />
                                    </FormControl>
                                    <CapitalizeText>
                                        {{ $t('models.banner.fields.is_enabled') }}
                                    </CapitalizeText>
                                </FormLabel>
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
