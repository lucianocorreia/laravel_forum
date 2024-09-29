<template>
    <Head>
        <link rel="canonical" :href="post.routes.show" />
    </Head>

    <AppLayout :title="post.title">
        <Container>
            <Pill :ref="route('posts.index', { topic: post.topic.slug })">{{ post.topic.name }}</Pill>
            <PageHeading class="mt-2">{{ post.title }}</PageHeading>
            <span class="block mt-1 text-sm text-gray-600">{{ formatedDate }} by {{ post.user.name }}</span>

            <article class="mt-6 prose-sm prose max-w-none" v-html="post.html">
            </article>

            <div class="mt-12">
                <h2 class="text-xl font-semibold">Comments</h2>

                <form v-if="$page.props.auth.user"
                    @submit.prevent="() => commentIdEditing ? updateComment() : addComment()" class="mt-4">
                    <div>
                        <InputLabel for="body" class="sr-only">Comment</InputLabel>
                        <MarkdownEditor ref="commentTextareaRef" id="body" v-model="commentForm.body"
                            editorClass="min-h-[160px]" placeholder="Enter som text..." />
                        <InputError :message="commentForm.errors.body" class="mt-1" />
                    </div>

                    <PrimaryButton type="submit" class="mt-2" :disabled="commentForm.processing"
                        v-text="commentIdEditing ? 'Update Comment' : 'Add Comment'">
                    </PrimaryButton>
                    <SecondaryButton v-if="commentIdEditing" @click="cancelEditComment" class="ml-2">
                        Cancel
                    </SecondaryButton>
                </form>

                <ul class="mt-4 divide-y">
                    <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4">
                        <Comment :comment="comment" @delete="deleteComment" @edit="editComment" />
                    </li>
                </ul>

                <Pagination :meta="comments.meta" :only="['comments']" />
            </div>
        </Container>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import { computed, ref } from "vue";
import Pagination from "@/Components/Pagination.vue";
import { relativeDate } from "@/Utils/date";
import Comment from "@/Components/Comment.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm, router, Head } from "@inertiajs/vue3";
import Textarea from "@/Components/Textarea.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useConfirm } from "@/Utils/Composables/useConfirm";
import MarkdownEditor from "@/Components/MarkdownEditor.vue";
import PageHeading from "@/Components/PageHeading.vue";
import Pill from "@/Components/Pill.vue";

const props = defineProps(["post", "comments"]);

const formatedDate = computed(() =>
    relativeDate(props.post.created_at)
);

const commentForm = useForm({
    body: "",
});

const { confirmation } = useConfirm();

const commentTextareaRef = ref(null);
const commentIdEditing = ref(null);
const commmentEditing = computed(() => {
    return props.comments.data.find((comment) => comment.id === commentIdEditing.value);
});

const editComment = (commentId) => {
    commentIdEditing.value = commentId;
    commentForm.body = commmentEditing.value?.body;

    commentTextareaRef.value?.focus();
};

const cancelEditComment = () => {
    commentIdEditing.value = null;
    commentForm.reset();
};

const addComment = () => {
    commentForm.post(route("posts.comments.store", props.post.id), {
        preserveScroll: true,
        onSuccess: () => {
            commentForm.reset();
        },
    });
};

const updateComment = async () => {
    if (! await confirmation('Are you sure you want to update this comment?')) {
        commentTextareaRef.value?.focus();
        return;
    }

    commentForm.put(route("comments.update", { comment: commentIdEditing.value, page: props.comments.meta.current_page }), {
        preserveScroll: true,
        onSuccess: () => {
            commentIdEditing.value = null;
            commentForm.reset();
        },
    });
};

const deleteComment = async (commentId) => {
    if (! await confirmation('Are you sure you want to delete this comment?')) {
        return;
    }

    router.delete(route('comments.destroy', { comment: commentId, page: props.comments.data.length > 1 ? props.comments.meta.current_page : Math.max(props.comments.meta.current_page - 1, 1) }), {
        preserveScroll: true,
        onSuccess: () => {
            // Do something after the comment is deleted
        },
    });
};

</script>
