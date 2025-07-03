<script setup lang="ts">
import ProjectDepartmentCombobox from '@/components/project-department/ProjectDepartmentCombobox.vue';
import { DatePicker } from '@/components/ui/custom/date-picker';
import {
    FormContent,
    FormControl,
    FormError,
    FormField,
    FormLabel,
    FormProps,
    injectFormContext,
} from '@/components/ui/custom/form';
import { TextInput } from '@/components/ui/custom/input';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { EmployeeFormData } from '@/composables';

defineProps<FormProps>();

const { form } = injectFormContext<EmployeeFormData>();
</script>

<template>
    <FormContent class="sm:grid-cols-2">
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.project_department.name.one') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <ProjectDepartmentCombobox v-model="form.project_department" :autofocus />
            </FormControl>
        </FormField>
        <FormField required class="col-start-1">
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.employee.fields.first_name') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <TextInput v-model="form.first_name" :autofocus />
            </FormControl>
            <FormError :message="form.errors.first_name" />
        </FormField>
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.employee.fields.last_name') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <TextInput v-model="form.last_name" />
            </FormControl>
            <FormError :message="form.errors.last_name" />
        </FormField>
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.employee.fields.email') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <TextInput v-model="form.email" type="email" />
            </FormControl>
            <FormError :message="form.errors.email" />
        </FormField>
        <FormField>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.employee.fields.phone') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <TextInput v-model="form.phone" type="tel" />
            </FormControl>
            <FormError :message="form.errors.phone" />
        </FormField>
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.employee.fields.starts_at') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <DatePicker v-model="form.starts_at" />
            </FormControl>
            <FormError :message="form.errors.starts_at" />
        </FormField>
    </FormContent>
</template>
