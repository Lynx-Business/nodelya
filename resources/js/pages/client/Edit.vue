<script setup lang="ts">
import ClientCommentForm from '@/components/client/ClientCommentForm.vue';
import ClientForm from '@/components/client/ClientForm.vue';
import { Card } from '@/components/ui/card';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { Section, SectionContent, SectionFooter, SectionHeader, SectionTitle } from '@/components/ui/custom/section';
import { useLayout } from '@/composables';
import { useClientForm } from '@/composables/forms/clients/useClientForm';
import { useCommentForm } from '@/composables/forms/comment/useCommentForm';
import ClientFormLayout from '@/layouts/client/ClientFormLayout.vue';
import { ClientFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

defineOptions({
    layout: useLayout(ClientFormLayout, () => ({
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
const commentForm = useCommentForm();

function submit() {
    const { client } = props;
    form.put(route('clients.update', { client: client! }));
}
</script>

<template>
    <Head :title="$t('pages.clients.update.title')" />

    <div>
        <Card>
            <Form :form :disabled="!client?.can_update" @submit="submit()">
                <Section>
                    <SectionHeader>
                        <SectionTitle>
                            {{ $t('pages.clients.update.title') }}
                        </SectionTitle>
                    </SectionHeader>
                    <SectionContent>
                        <ClientForm />
                    </SectionContent>
                    <SectionFooter>
                        <FormSubmitButton />
                    </SectionFooter>
                </Section>
            </Form>
        </Card>
        <Card class="mt-4">
            <Form :form="commentForm" :disabled="!client?.can_update">
                <Section>
                    <SectionContent>
                        <ClientCommentForm />
                    </SectionContent>
                </Section>
            </Form>
        </Card>
    </div>
</template>
