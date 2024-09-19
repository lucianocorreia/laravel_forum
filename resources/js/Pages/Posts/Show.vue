<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import { computed } from "vue";
import Pagination from "@/Components/Pagination.vue";
import { relativeDate } from "@/Utils/date";
import Comment from "@/Components/Comment.vue";

const props = defineProps(["post", "comments"]);

const formatedDate = computed(() =>
    relativeDate(props.post.created_at)
);
</script>

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

                <ul class="mt-4 divide-y">
                    <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4">
                        <Comment :comment="comment" />
                    </li>
                </ul>

                <Pagination :meta="comments.meta" :only="['comments']"  />
            </div>
        </Container>
    </AppLayout>
</template>
