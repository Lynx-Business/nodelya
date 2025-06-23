<script setup lang="ts">
import TeamForm from '@/components/team/TeamForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { Section, SectionContent, SectionFooter } from '@/components/ui/custom/section';
import { useLayout, useTeamForm } from '@/composables';
import { AuthSetupLayout } from '@/layouts';
import { Head } from '@inertiajs/vue3';

defineOptions({
    layout: useLayout(AuthSetupLayout, () => ({
        step: 1,
    })),
});

const form = useTeamForm();

function submit() {
    form.post(route('auth.setup.step-one.update'));
}
</script>

<template>
    <Head :title="$t('pages.auth.setup.step_one.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionContent>
                <TeamForm autofocus />
            </SectionContent>
            <SectionFooter class="grid">
                <FormSubmitButton />
            </SectionFooter>
        </Section>
    </Form>
</template>
