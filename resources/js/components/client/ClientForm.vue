<script setup lang="ts">
import {
    FormContent,
    FormControl,
    FormError,
    FormField,
    FormLabel,
    injectFormContext,
} from '@/components/ui/custom/form';
import { TextInput } from '@/components/ui/custom/input';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { ClientFormData } from '@/composables';
import { PlusIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import Button from '../ui/button/Button.vue';
import Textarea from '../ui/textarea/Textarea.vue';

const { form } = injectFormContext<ClientFormData>();

const newComment = ref({ id: 0, message: '' });

function addComment() {
    // id nullable TODO
    console.log(form.comments);

    form.comments.push(newComment.value);
}

function removeComment(index: number) {
    form.comments.splice(index, 1);
}
</script>

<template>
    <FormContent class="sm:grid-cols-2">
        <FormField required>
            <FormLabel>
                <CapitalizeText>
                    {{ $t('models.client.fields.name') }}
                </CapitalizeText>
            </FormLabel>
            <FormControl>
                <TextInput v-model="form.name" />
            </FormControl>
            <FormError :message="form.errors.name" />
        </FormField>

        <div class="col-span-full grid grid-cols-1 gap-4 md:grid-cols-2">
            <FormField class="col-span-full" required>
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('data.address.fields.address') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <TextInput v-model="form.address.address" />
                </FormControl>
                <FormError :message="form.errors['address.address']" />
            </FormField>

            <FormField class="col-span-full">
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('data.address.fields.address_complement') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <TextInput v-model="form.address.address_complement" />
                </FormControl>
                <FormError :message="form.errors['address.address_complement']" />
            </FormField>

            <FormField required>
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('data.address.fields.city') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <TextInput v-model="form.address.city" />
                </FormControl>
                <FormError :message="form.errors['address.city']" />
            </FormField>

            <FormField required>
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('data.address.fields.postal_code') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <TextInput v-model="form.address.postal_code" />
                </FormControl>
                <FormError :message="form.errors['address.postal_code']" />
            </FormField>

            <FormField class="col-span-full" required>
                <FormLabel>
                    <CapitalizeText>
                        {{ $t('data.address.fields.country') }}
                    </CapitalizeText>
                </FormLabel>
                <FormControl>
                    <TextInput v-model="form.address.country" />
                </FormControl>
                <FormError :message="form.errors['address.country']" />
            </FormField>
        </div>

        <div class="col-span-full">
            <h3 class="mb-4 text-lg font-medium">Commentaires</h3>

            <div class="mb-4 flex items-center gap-2 sm:grid-cols-2">
                <FormField class="col-span-full">
                    <FormLabel>
                        <CapitalizeText> Message </CapitalizeText>
                    </FormLabel>
                    <FormControl>
                        <Textarea v-model="newComment.message" />
                    </FormControl>
                </FormField>

                <Button type="button" variant="outline" @click="addComment">
                    <PlusIcon class="mr-2 h-4 w-4" />
                    Ajouter un commentaire
                </Button>
            </div>

            <div v-for="(comment, index) in form.comments" :key="index" class="mb-4 rounded-lg border p-4">
                <div class="mb-2 flex items-start justify-between">
                    <FormLabel>Message</FormLabel>
                    <Button type="button" variant="ghost" size="icon" @click="removeComment(index)">
                        <Trash2Icon class="h-4 w-4" />
                    </Button>
                </div>

                <FormField required>
                    <FormControl>
                        <Textarea v-model="comment.message" />
                    </FormControl>
                </FormField>

                <div v-if="comment.id" class="text-muted-foreground mt-2 flex items-center gap-2 text-xs">
                    <Badge variant="secondary"> Modifié </Badge>
                    <span>ID: {{ comment.id }}</span>
                </div>
            </div>
        </div>
    </FormContent>
</template>
