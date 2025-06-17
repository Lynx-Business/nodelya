<script setup lang="ts">
import ClientForm from '@/components/client/ClientForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { Section, SectionContent, SectionFooter, SectionHeader, SectionTitle } from '@/components/ui/custom/section';
import { useLayout } from '@/composables';
import { useClientForm } from '@/composables/forms/clients/useClientForm';
import { AdminLayout } from '@/layouts';
import { ClientFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

defineOptions({
    layout: useLayout(AdminLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.clients.index.title'),
                href: route('clients.index'),
            },
            {
                title: trans('pages.clients.create.title'),
                href: route('admin.banners.create'),
            },
        ],
    })),
});

defineProps<ClientFormProps>();
const form = useClientForm();

function submit() {
    form.post(route('admin.banners.store'));
}
</script>

<template>
    <Head :title="$t('pages.clients.create.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.clients.create.title') }}
                </SectionTitle>
            </SectionHeader>
            <SectionContent class="sm:flex">
                <ClientForm />
            </SectionContent>
            <SectionFooter>
                <FormSubmitButton />
            </SectionFooter>
        </Section>
    </Form>
</template>
