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
                title: trans('pages.teams.accounting_periods.edit.title'),
                href: route('teams.accounting-periods.edit', {
                    team: route().params.team,
                    accountingPeriod: route().params.accountingPeriod,
                }),
            },
        ],
    })),
});

const props = defineProps<AccountingPeriodFormProps>();

const form = useAccountingPeriodForm(props.accountingPeriod);

function submit() {
    const { team, accountingPeriod } = props;
    form.put(route('teams.accounting-periods.update', { team, accountingPeriod: accountingPeriod! }));
}
</script>

<template>
    <Head :title="$t('pages.teams.accounting_periods.edit.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.teams.accounting_periods.edit.title') }}
                </SectionTitle>
                <SectionDescription>
                    {{ $t('pages.teams.accounting_periods.edit.description') }}
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
