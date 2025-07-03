<script setup lang="ts">
import EmployeeForm from '@/components/employee/EmployeeForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import {
    Section,
    SectionContent,
    SectionDescription,
    SectionFooter,
    SectionHeader,
    SectionTitle,
} from '@/components/ui/custom/section';
import { useEmployeeForm, useLayout } from '@/composables';
import { AppLayout } from '@/layouts';
import { EmployeeFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.employees.index.title'),
                href: route('employees.index'),
            },
            {
                title: trans('pages.employees.create.title'),
                href: route('employees.create'),
            },
        ],
    })),
});

defineProps<EmployeeFormProps>();

const form = useEmployeeForm();

function submit() {
    form.post(route('employees.create'));
}
</script>

<template>
    <Head :title="$t('pages.employees.create.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.employees.create.title') }}
                </SectionTitle>
                <SectionDescription>
                    {{ $t('pages.employees.create.description') }}
                </SectionDescription>
            </SectionHeader>
            <SectionContent class="sm:flex">
                <EmployeeForm autofocus />
            </SectionContent>
            <SectionFooter>
                <FormSubmitButton />
            </SectionFooter>
        </Section>
    </Form>
</template>
