<script setup lang="ts">
import AccountingPeriodForm from '@/components/accounting-period/AccountingPeriodForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import {
    Section,
    SectionContent,
    SectionDescription,
    SectionFooter,
    SectionHeader,
    SectionTitle,
} from '@/components/ui/custom/section';
import { useAccountingPeriodForm, useLayout } from '@/composables';
import { AppLayout } from '@/layouts';
import { AccountingPeriodFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

defineOptions({
    layout: useLayout(AppLayout, () => ({
        breadcrumbs: [
            {
                title: trans('pages.teams.index.title'),
                href: route('teams.index'),
            },
            {
                title: route().params.team,
                href: route('teams.edit', { team: route().params.team }),
            },
            {
                title: trans('pages.teams.accounting_periods.index.title'),
                href: route('teams.accounting-periods.index', { team: route().params.team }),
            },
            {
                title: trans('pages.teams.accounting_periods.create.title'),
                href: route('teams.accounting-periods.create', { team: route().params.team }),
            },
        ],
    })),
});

const props = defineProps<AccountingPeriodFormProps>();

const form = useAccountingPeriodForm();

function submit() {
    const { team } = props;
    form.post(route('teams.accounting-periods.create', { team }));
}
</script>

<template>
    <Head :title="$t('pages.teams.accounting_periods.create.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.teams.accounting_periods.create.title') }}
                </SectionTitle>
                <SectionDescription>
                    {{ $t('pages.teams.accounting_periods.create.description') }}
                </SectionDescription>
            </SectionHeader>
            <SectionContent class="sm:flex">
                <AccountingPeriodForm autofocus />
            </SectionContent>
            <SectionFooter>
                <FormSubmitButton />
            </SectionFooter>
        </Section>
    </Form>
</template>
