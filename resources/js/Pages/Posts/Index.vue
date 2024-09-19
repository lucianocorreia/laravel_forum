<script setup>
import { Link } from "@inertiajs/vue3";
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { formatDistance, parseISO } from "date-fns";
import { relativeDate } from "@/Utils/date";

defineProps(["posts"]);

const formatedDate = (post) => relativeDate(post.created_at);
</script>

<template>
    <AppLayout>
        <Container>
            <ul class="divide-y">
                <li v-for="post in posts.data" :key="post.id">
                    <Link :href="route('posts.show', post.id)" class="block px-2 py-4 group">
                    <span class="text-lg font-bold group-hover:text-indigo-500">{{ post.title }}</span>
                    <span class="block mt-1 text-sm text-gray-600">{{ formatedDate(post) }} ago by {{ post.user.name
                        }}</span>
                    </Link>
                </li>
            </ul>

            <Pagination :meta="posts.meta" />
        </Container>
    </AppLayout>
</template>
