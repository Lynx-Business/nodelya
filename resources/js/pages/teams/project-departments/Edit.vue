<script setup lang="ts">
import ProjectDepartmentForm from '@/components/project-department/ProjectDepartmentForm.vue';
import { Form, FormSubmitButton } from '@/components/ui/custom/form';
import {
    Section,
    SectionContent,
    SectionDescription,
    SectionFooter,
    SectionHeader,
    SectionTitle,
} from '@/components/ui/custom/section';
import { useLayout, useProjectDepartmentForm } from '@/composables';
import { AppLayout } from '@/layouts';
import { ProjectDepartmentFormProps } from '@/types';
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
                title: trans('pages.teams.project_departments.index.title'),
                href: route('teams.project-departments.index', { team: route().params.team }),
            },
            {
                title: trans('pages.teams.project_departments.edit.title'),
                href: route('teams.project-departments.edit', {
                    team: route().params.team,
                    projectDepartment: route().params.projectDepartment,
                }),
            },
        ],
    })),
});

const props = defineProps<ProjectDepartmentFormProps>();

const form = useProjectDepartmentForm(props.projectDepartment);

function submit() {
    const { team, projectDepartment } = props;
    form.put(route('teams.project-departments.update', { team, projectDepartment: projectDepartment! }));
}
</script>

<template>
    <Head :title="$t('pages.teams.project_departments.edit.title')" />

    <Form :form @submit="submit()">
        <Section>
            <SectionHeader>
                <SectionTitle>
                    {{ $t('pages.teams.project_departments.edit.title') }}
                </SectionTitle>
                <SectionDescription>
                    {{ $t('pages.teams.project_departments.edit.description') }}
                </SectionDescription>
            </SectionHeader>
            <SectionContent class="sm:flex">
                <ProjectDepartmentForm />
            </SectionContent>
            <SectionFooter>
                <FormSubmitButton />
            </SectionFooter>
        </Section>
    </Form>
</template>
