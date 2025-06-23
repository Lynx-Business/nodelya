<script setup lang="ts">
import AccountingPeriodForm from '@/components/accounting-period/AccountingPeriodForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { Section, SectionContent, SectionFooter } from '@/components/ui/custom/section';
import { useAccountingPeriodForm, useAuth, useLayout } from '@/composables';
import { AuthSetupLayout } from '@/layouts';
import { Head } from '@inertiajs/vue3';

defineOptions({
    layout: useLayout(AuthSetupLayout, () => ({
        step: 2,
    })),
});

const { team } = useAuth();
const form = useAccountingPeriodForm();

function submit() {
    form.post(route('auth.setup.step-two.update'));
}
</script>

<template>
    <Head :title="$t('pages.auth.setup.step_two.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionContent>
                <AccountingPeriodForm autofocus />
            </SectionContent>
            <SectionFooter class="grid">
                <FormSubmitButton />
            </SectionFooter>
        </Section>
    </Form>
</template>
