<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { FormContent, FormControl, FormField, FormLabel, injectFormContext } from '@/components/ui/custom/form';
import { CapitalizeText } from '@/components/ui/custom/typography';
import { Textarea } from '@/components/ui/textarea';
import { usePageProp } from '@/composables';
import { CommentFormData, useCommentForm } from '@/composables/forms/comment/useCommentForm';
import { ClientResource, CommentResource } from '@/types';
import { useAxios } from '@vueuse/integrations/useAxios.mjs';
import { trans } from 'laravel-vue-i18n';
import { CheckIcon, EditIcon, PlusIcon, Trash2Icon, XIcon } from 'lucide-vue-next';
import { ref } from 'vue';

const { isLoading, execute } = useAxios<CommentResource>();

const comments = usePageProp<Array<CommentResource>>('comments');
console.log('comments => ', comments.value);

const client = usePageProp<ClientResource>('client');

const localComments = ref(
    (comments?.value ?? []).map((comment: CommentResource) => ({
        ...comment,
        isEditing: false,
        form: useCommentForm(comment),
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
            form: useCommentForm(data.value),
        });
        form.reset();
    }
}

async function updateComment(comment: any) {
    if (!comment.form.message?.trim()) return;

    const payload = {
        ...comment.form,
        model_type: 'Client',
        model_id: client.value?.id,
    };

    const { data } = await execute(route('comments.update', { comment: comment.id }), {
        method: 'PUT',
        data: payload,
    });

    if (data.value) {
        comment.message = data.value.message;
        comment.updated_at = data.value.updated_at;
        comment.is_edited = true;
        comment.isEditing = false;
    }
}

function cancelEdit(comment: any) {
    comment.isEditing = false;
    comment.form.reset();
}

async function removeComment(comment: any) {
    if (!confirm(trans('pages.clients.comments.delete_confirm'))) return;

    const { data } = await execute(route('comments.destroy', { comment: comment.id }), {
        method: 'DELETE',
    });

    if (data) {
        localComments.value = localComments.value.filter((c) => c !== comment);
    }
}
</script>

<template>
    <FormContent class="sm:grid-cols-2">
        <div class="col-span-full">
            <h3 class="mb-4 text-lg font-medium">{{ $t('pages.clients.comments.title') }}</h3>

            <div class="mb-4 grid grid-cols-[1fr_auto] items-end gap-4">
                <FormField class="!col-span-full">
                    <FormLabel>
                        <CapitalizeText> {{ $t('pages.clients.comments.new') }} </CapitalizeText>
                    </FormLabel>
                    <FormControl>
                        <Textarea v-model="form.message" :disabled="isLoading || disabled" />
                    </FormControl>
                </FormField>

                <Button
                    type="button"
                    variant="outline"
                    @click="addComment"
                    :disabled="isLoading || !form.message.trim() || disabled"
                >
                    <PlusIcon class="mr-2 h-4 w-4" />
                    {{ $t('pages.clients.comments.add') }}
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
                                type="button"
                                variant="ghost"
                                size="icon"
                                @click="comment.isEditing = true"
                                :disabled="isLoading || disabled || !comment.can_update"
                            >
                                <EditIcon class="h-4 w-4" />
                            </Button>
                            <Button
                                type="button"
                                variant="ghost"
                                size="icon"
                                @click="removeComment(comment)"
                                :disabled="isLoading || disabled || !comment.can_delete"
                            >
                                <Trash2Icon class="h-4 w-4" />
                            </Button>
                        </div>
                    </div>
                    <p class="whitespace-pre-wrap">{{ comment.message }}</p>
                </div>

                <div v-else>
                    <FormField required>
                        <FormLabel>
                            <CapitalizeText> {{ $t('pages.clients.comments.edit') }} </CapitalizeText>
                        </FormLabel>
                        <FormControl>
                            <Textarea v-model="comment.form.message" :disabled="isLoading || disabled" />
                        </FormControl>
                    </FormField>
                    <div class="mt-4 flex justify-end gap-2">
                        <Button type="button" variant="outline" @click="cancelEdit(comment)" :disabled="isLoading">
                            <XIcon class="mr-2 h-4 w-4" />
                            {{ $t('pages.clients.comments.cancel') }}
                        </Button>
                        <Button
                            type="button"
                            @click="updateComment(comment)"
                            :disabled="isLoading || !comment.form.message.trim() || disabled || !comment.can_update"
                        >
                            <CheckIcon class="mr-2 h-4 w-4" />
                            {{ $t('pages.clients.comments.save') }}
                        </Button>
                    </div>
                </div>
                <div v-if="comment.id" class="text-muted-foreground mt-3 flex items-center gap-2 text-xs">
                    <span v-if="comment.is_edited">
                        • {{ $t('pages.clients.comments.edited_on') }}
                        {{ new Date(comment.updated_at).toLocaleString() }}
                    </span>
                    <span v-else>
                        • {{ $t('pages.clients.comments.created_on') }}
                        {{ new Date(comment.created_at).toLocaleString() }}
                    </span>
                </div>
            </div>
        </div>
    </FormContent>
</template>
