<script setup lang="ts">
import ClientForm from '@/components/client/ClientForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { Section, SectionContent, SectionFooter, SectionHeader, SectionTitle } from '@/components/ui/custom/section';
import { useLayout } from '@/composables';
import { useClientForm } from '@/composables/forms/clients/useClientForm';
import { AppLayout } from '@/layouts';
import { ClientFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.clients.index.title'),
                href: route('clients.index'),
            },
            {
                title: route().params.client,
                href: route('clients.edit', { client: route().params.client }),
            },
        ],
    })),
});

const props = defineProps<ClientFormProps>();
const form = useClientForm(props.client);

function submit() {
    const { client } = props;
    form.put(route('clients.update', { client: client! }));
}
</script>

<template>
    <Head :title="$t('pages.clients.update.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.clients.update.title') }}
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
