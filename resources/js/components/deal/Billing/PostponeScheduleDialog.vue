<script lang="ts" setup>
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

const postponeMonths = defineModel<number>('postponeMonths');
const postponeSchedules = defineModel<() => void>('postponeSchedules');
const showPostponeDialog = defineModel<boolean>('showPostponeDialog');
</script>

<template>
    <AlertDialog :open="showPostponeDialog">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>{{ $t('pages.deals.billings.postpone_schedule.title') }}</AlertDialogTitle>
                <AlertDialogDescription>
                    {{ $t('pages.deals.billings.postpone_schedule.description') }}
                </AlertDialogDescription>
            </AlertDialogHeader>

            <div class="py-4">
                <Input
                    v-model="postponeMonths"
                    type="number"
                    min="1"
                    placeholder="Nombre de mois"
                    class="text-center text-lg"
                    @keyup.enter="postponeSchedules"
                />
            </div>

            <AlertDialogFooter>
                <AlertDialogCancel @click="showPostponeDialog = false"> {{ $t('cancel') }} </AlertDialogCancel>
                <Button @click="postponeSchedules"> {{ $t('confirm') }} </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
