<script setup lang="ts">
import EmployeeEndsAtForm from '@/components/employee/EmployeeEndsAtForm.vue';
import EmployeeForm from '@/components/employee/EmployeeForm.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { InertiaLink } from '@/components/ui/custom/link';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { useEmployeeEndsAtForm, useEmployeeForm, useLayout } from '@/composables';
import { EmployeesFormLayout } from '@/layouts';
import { EmployeeFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Trash2Icon } from 'lucide-vue-next';
import { computed } from 'vue';

defineOptions({
    layout: useLayout(EmployeesFormLayout, () => ({})),
});

const props = defineProps<EmployeeFormProps>();

const employee = computed(() => props.employee!);

const form = useEmployeeForm(employee.value);
function submit() {
    form.put(route('employees.update', { employee: employee.value }));
}

const endsAtForm = useEmployeeEndsAtForm(employee.value);
function endsAtSubmit() {
    endsAtForm.patch(route('employees.ends-at.update', { employee: employee.value }));
}
const endsAtDisabled = computed(() => !employee.value.can_update);
</script>

<template>
    <Head :title="$t('pages.employees.edit.title')" />

    <Form :form :disabled="!employee.can_update" @submit="submit()">
        <Card>
            <CardHeader>
                <CardTitle>
                    {{ $t('pages.employees.edit.title') }}
                </CardTitle>
                <CardDescription>
                    {{ $t('pages.employees.edit.description') }}
                </CardDescription>
            </CardHeader>
            <CardContent class="sm:flex">
                <EmployeeForm autofocus />
            </CardContent>
            <CardFooter>
                <FormSubmitButton />
            </CardFooter>
        </Card>
    </Form>

    <Form :form="endsAtForm" :disabled="endsAtDisabled" @submit="endsAtSubmit()">
        <Card>
            <CardHeader>
                <CardTitle>
                    {{ $t('pages.employees.edit.ends_at.title') }}
                </CardTitle>
                <CardDescription>
                    {{ $t('pages.employees.edit.ends_at.description') }}
                </CardDescription>
            </CardHeader>
            <CardContent class="sm:flex">
                <EmployeeEndsAtForm :disabled="employee.ends_at != undefined" />
            </CardContent>
            <CardFooter v-if="!endsAtDisabled">
                <FormSubmitButton v-if="!employee.ends_at" />
                <Button v-else variant="destructive" as-child>
                    <InertiaLink
                        :href="route('employees.ends-at.destroy', { employee })"
                        method="delete"
                        :on-success="() => (endsAtForm.ends_at = '')"
                    >
                        <Trash2Icon />
                        <CapitalizeText>
                            {{ $t('pages.employees.edit.ends_at.actions.delete') }}
                        </CapitalizeText>
                    </InertiaLink>
                </Button>
            </CardFooter>
        </Card>
    </Form>
</template>
