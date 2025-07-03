<script setup lang="ts">
import EmployeeForm from '@/components/employee/EmployeeForm.vue';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import { useEmployeeForm, useLayout } from '@/composables';
import { EmployeesFormLayout } from '@/layouts';
import { EmployeeFormProps } from '@/types';
import { Head } from '@inertiajs/vue3';

defineOptions({
    layout: useLayout(EmployeesFormLayout, () => ({})),
});

const props = defineProps<EmployeeFormProps>();

const form = useEmployeeForm(props.employee);

function submit() {
    const { employee } = props;
    form.put(route('employees.update', { employee: employee! }));
}
</script>

<template>
    <Head :title="$t('pages.employees.edit.title')" />

    <Form :form :disabled="!employee?.can_update" @submit="submit()">
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
</template>
