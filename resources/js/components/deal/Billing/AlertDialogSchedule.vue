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
                <AlertDialogTitle>Reporter les factures</AlertDialogTitle>
                <AlertDialogDescription>
                    Combien de mois voulez-vous reporter les factures à partir de cette échéance ?
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
                <AlertDialogCancel @click="showPostponeDialog = false"> Annuler </AlertDialogCancel>
                <Button @click="postponeSchedules"> Confirmer </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
