<script setup lang="ts">
import { TextLink } from '@/components/ui/custom/link';
import { Section, SectionContent } from '@/components/ui/custom/section';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { useLayout } from '@/composables';
import { AuthLayout } from '@/layouts';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

defineOptions({
    layout: useLayout(AuthLayout, () => ({
        title: trans('pages.auth.setup.not_ready.title'),
        description: trans('pages.auth.setup.not_ready.description'),
    })),
});
</script>

<template>
    <Head :title="$t('pages.auth.setup.not_ready.title')" />

    <Section>
        <SectionContent class="grid">
            <div class="text-muted-foreground space-x-1 text-center text-sm">
                {{ $t('pages.auth.setup.not_ready.not_you') }}
                <CapitalizeText as-child>
                    <TextLink :href="route('logout')" method="post">
                        {{ $t('logout') }}
                    </TextLink>
                </CapitalizeText>
            </div>
        </SectionContent>
    </Section>
</template>
