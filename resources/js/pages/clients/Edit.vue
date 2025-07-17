<script setup lang="ts">
import ClientCommentForm from '@/components/client/ClientCommentForm.vue';
import ClientForm from '@/components/client/ClientForm.vue';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { Section, SectionContent } from '@/components/ui/custom/section';
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

    <Section>
        <SectionContent>
            <Form :form :disabled="!client?.can_update" @submit="submit()">
                <Card>
                    <CardHeader>
                        <CardTitle>
                            {{ $t('pages.clients.update.title') }}
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <ClientForm />
                    </CardContent>
                    <CardFooter>
                        <FormSubmitButton />
                    </CardFooter>
                </Card>
            </Form>
        </SectionContent>
        <SectionContent class="mt-4">
            <Form :form="commentForm" :disabled="!client?.can_update">
                <Card>
                    <CardContent>
                        <ClientCommentForm />
                    </CardContent>
                </Card>
            </Form>
        </SectionContent>
    </Section>
</template>
