<script setup lang="ts">
import ContractorForm from '@/components/contractor/ContractorForm.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { InertiaLink } from '@/components/ui/custom/link';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { useContractorForm, useLayout } from '@/composables';
import { ContractorsFormLayout } from '@/layouts';
import { ContractorFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { RefreshCwIcon, XIcon } from 'lucide-vue-next';
import { computed } from 'vue';

defineOptions({
    layout: useLayout(ContractorsFormLayout, () => ({})),
});

const props = defineProps<ContractorFormProps>();

const contractor = computed(() => props.contractor!);

const form = useContractorForm(contractor.value);
function submit() {
    form.put(route('contractors.update', { contractor: contractor.value }));
}

const endsAtDisabled = computed(() => !contractor.value.can_update);
</script>

<template>
    <Head :title="$t('pages.contractors.edit.title')" />

    <Form :form :disabled="!contractor.can_update" @submit="submit()">
        <Card>
            <CardHeader>
                <CardTitle>
                    {{ $t('pages.contractors.edit.title') }}
                </CardTitle>
                <CardDescription>
                    {{ $t('pages.contractors.edit.description') }}
                </CardDescription>
            </CardHeader>
            <CardContent class="sm:flex">
                <ContractorForm autofocus />
            </CardContent>
            <CardFooter>
                <FormSubmitButton />
            </CardFooter>
        </Card>
    </Form>

    <Card>
        <CardHeader>
            <CardTitle>
                {{ $t('pages.contractors.edit.ends_at.title') }}
            </CardTitle>
            <CardDescription>
                {{ $t('pages.contractors.edit.ends_at.description') }}
            </CardDescription>
        </CardHeader>
        <CardFooter v-if="!endsAtDisabled">
            <Button v-if="!contractor.ends_at" as-child>
                <InertiaLink :href="route('contractors.ends-at.update', { contractor })" method="patch">
                    <XIcon />
                    <CapitalizeText>
                        {{ $t('pages.contractors.edit.ends_at.actions.update') }}
                    </CapitalizeText>
                </InertiaLink>
            </Button>
            <Button v-else variant="destructive" as-child>
                <InertiaLink :href="route('contractors.ends-at.destroy', { contractor })" method="delete">
                    <RefreshCwIcon />
                    <CapitalizeText>
                        {{ $t('pages.contractors.edit.ends_at.actions.delete') }}
                    </CapitalizeText>
                </InertiaLink>
            </Button>
        </CardFooter>
    </Card>
</template>
