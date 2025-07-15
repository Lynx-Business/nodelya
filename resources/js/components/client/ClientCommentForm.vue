<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { FormContent, FormControl, FormField, FormLabel, injectFormContext } from '@/components/ui/custom/form';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { Textarea } from '@/components/ui/textarea';
import { useAlert, useFormatter, usePageProp } from '@/composables';
import { CommentFormData, useCommentForm } from '@/composables/forms/comment/useCommentForm';
import { ClientResource, CommentResource } from '@/types';
import { useAxios } from '@vueuse/integrations/useAxios.mjs';
import { trans } from 'laravel-vue-i18n';
import { CheckIcon, EditIcon, PlusIcon, Trash2Icon, XIcon } from 'lucide-vue-next';
import { ref } from 'vue';

const { isLoading, execute } = useAxios<CommentResource>();

const comments = usePageProp<Array<CommentResource>>('comments');
const alert = useAlert();
const format = useFormatter();

const client = usePageProp<ClientResource>('client');

const localComments = ref(
    (comments?.value ?? []).map((comment: CommentResource) => ({
        ...comment,
        isEditing: false,
    })),
);

const { form, disabled } = injectFormContext<CommentFormData>();

async function addComment() {
    if (!form.message.trim()) return;

    ((form.model_type = 'Client'), (form.model_id = client.value?.id ?? 0));
    const payload = {
        ...form,
    };

    const { data } = await execute(route('comments.store'), {
        method: 'POST',
        data: payload,
    });

    if (data.value) {
        localComments.value.push({
            ...data.value,
            isEditing: false,
        });
        form.reset();
    }
}

async function updateComment(comment: CommentResource) {
    let commentFrom = useCommentForm(comment);
    if (!commentFrom.message?.trim()) return;

    ((commentFrom.model_type = 'Client'), (commentFrom.model_id = client.value?.id ?? 0));
    const payload = {
        ...commentFrom,
    };
    const { data } = await execute(route('comments.update', { comment: comment }), {
        method: 'PUT',
        data: payload,
    });

    if (data.value) {
        const idx = localComments.value.findIndex((c) => c.id === comment.id);
        if (idx !== -1) {
            localComments.value[idx] = {
                ...localComments.value[idx],
                ...data.value,
                isEditing: false,
            };
        }
    }
}

function cancelEdit(comment: CommentResource) {
    const idx = localComments.value.findIndex((c) => c.id === comment.id);
    if (idx !== -1) {
        localComments.value[idx].isEditing = false;
    }
}

async function removeComment(comment: CommentResource) {
    alert({
        variant: 'warning',
        description: trans('components.clients.comments.delete_confirm'),
        callback: async () => {
            const { data } = await execute(route('comments.destroy', { comment: comment }), {
                method: 'DELETE',
            });

            if (data.value) {
                localComments.value = localComments.value.filter((c) => c !== comment);
            }
        },
    });
}
</script>

<template>
    <FormContent class="sm:grid-cols-2">
        <div class="col-span-full">
            <h3 class="mb-4 text-lg font-medium">{{ $t('components.comments.fields.title') }}</h3>

            <div class="mb-4 grid grid-cols-[1fr_auto] items-end gap-4">
                <FormField class="!col-span-full">
                    <FormLabel>
                        <CapitalizeText> {{ $t('components.comments.fields.new') }} </CapitalizeText>
                    </FormLabel>
                    <FormControl>
                        <Textarea v-model="form.message" :disabled="isLoading || disabled" />
                    </FormControl>
                </FormField>

                <Button variant="outline" @click="addComment" :disabled="isLoading || !form.message.trim() || disabled">
                    <PlusIcon />
                    {{ $t('components.comments.fields.add') }}
                </Button>
            </div>

            <div
                v-for="(comment, index) in localComments"
                :key="comment.id || index"
                class="mb-4 rounded-lg border p-4"
            >
                <div v-if="!comment.isEditing">
                    <div class="mb-2 flex items-start justify-between">
                        <h4 class="font-medium">{{ comment.creator?.full_name }}</h4>
                        <div class="flex gap-2">
                            <Button
                                variant="ghost"
                                size="icon"
                                @click="comment.isEditing = true"
                                :disabled="isLoading || disabled || !comment.can_update"
                            >
                                <EditIcon />
                            </Button>
                            <Button
                                variant="ghost"
                                size="icon"
                                @click="removeComment(comment)"
                                :disabled="isLoading || disabled || !comment.can_delete"
                            >
                                <Trash2Icon />
                            </Button>
                        </div>
                    </div>
                    <p class="whitespace-pre-wrap">{{ comment.message }}</p>
                </div>

                <div v-else>
                    <FormField required>
                        <FormLabel>
                            <CapitalizeText> {{ $t('components.comments.fields.edit') }} </CapitalizeText>
                        </FormLabel>
                        <FormControl>
                            <Textarea v-model="comment.message" :disabled="isLoading || disabled" />
                        </FormControl>
                    </FormField>
                    <div class="mt-4 flex justify-end gap-2">
                        <Button variant="outline" @click="cancelEdit(comment)" :disabled="isLoading">
                            <XIcon />
                            {{ $t('components.comments.fields.cancel') }}
                        </Button>
                        <Button
                            @click="updateComment(comment)"
                            :disabled="isLoading || !comment.message.trim() || disabled || !comment.can_update"
                        >
                            <CheckIcon />
                            {{ $t('components.comments.fields.save') }}
                        </Button>
                    </div>
                </div>
                <div v-if="comment.id" class="text-muted-foreground mt-3 flex items-center gap-2 text-xs">
                    <span v-if="comment.is_edited">
                        • {{ $t('components.comments.fields.edited_on') }}
                        {{ format.date(comment.updated_at) }}
                    </span>
                    <span v-else>
                        • {{ $t('components.comments.fields.created_on') }}
                        {{ format.date(comment.created_at) }}
                    </span>
                </div>
            </div>
        </div>
    </FormContent>
</template>
