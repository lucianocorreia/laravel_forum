<template>
    <AppLayout :title="post.title">
        <Container>
            <h1 class="text-2xl font-bold">{{ post.title }}</h1>
            <span class="block mt-1 text-sm text-gray-600">{{ formatedDate }} ago by {{ post.user.name }}</span>

            <article class="mt-6">
                <pre class="font-sans whitespace-pre-wrap">{{ post.body }}</pre>
            </article>

            <div class="mt-12">
                <h2 class="text-xl font-semibold">Comments</h2>

                <form v-if="$page.props.auth.user" @submit.prevent="addComment" class="mt-4">
                    <div>
                        <InputLabel for="body" class="sr-only">Comment</InputLabel>
                        <Textarea id="body" v-model="commentForm.body" rows="4"
                            placeholder="Speak your mind Spock..." />
                        <InputError :message="commentForm.errors.body" class="mt-1" />
                    </div>

                    <PrimaryButton type="submit" class="mt-2" :disabled="commentForm.processing">Add Comment
                    </PrimaryButton>
                </form>

                <ul class="mt-4 divide-y">
                    <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4">
                        <Comment :comment="comment" @delete="deleteComment" />
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
import { computed } from "vue";
import Pagination from "@/Components/Pagination.vue";
import { relativeDate } from "@/Utils/date";
import Comment from "@/Components/Comment.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm, router } from "@inertiajs/vue3";
import Textarea from "@/Components/Textarea.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps(["post", "comments"]);

const formatedDate = computed(() =>
    relativeDate(props.post.created_at)
);

const commentForm = useForm({
    body: "",
});

const addComment = () => {
    console.log(props.post.id);
    commentForm.post(route("posts.comments.store", props.post.id), {
        preserveScroll: true,
        onSuccess: () => {
            commentForm.reset();
        },
    });
};

const deleteComment = (commentId) => router.delete(route('comments.destroy', { comment: commentId, page: props.comments.meta.current_page }), {
    preserveScroll: true,
    onSuccess: () => {
        // Do something after the comment is deleted
    },
});

</script>
