<script setup lang="ts">
import ProjectDepartmentCombobox from '@/components/project-department/ProjectDepartmentCombobox.vue';
import {
    FormContent,
    FormControl,
    FormError,
    FormField,
    FormLabel,
    injectFormContext,
} from '@/components/ui/custom/form';
import { TextInput } from '@/components/ui/custom/input';
import { CommercialDealValidateFormData } from '@/composables';

const { form } = injectFormContext<CommercialDealValidateFormData>();
</script>

<template>
    <FormContent class="sm:grid-cols-2">
        <FormField>
            <FormLabel>{{ $t('models.deal.commercial.fields.reference_commande') }}</FormLabel>
            <FormControl>
                <TextInput :model-value="form.reference" disabled />
            </FormControl>
        </FormField>

        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.project_department.name.one') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <ProjectDepartmentCombobox v-model="form.project_department" />
            </FormControl>
            <FormError :message="form.errors.project_department" />
        </FormField>
    </FormContent>
</template>
