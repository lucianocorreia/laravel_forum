<script setup>
import { Link } from "@inertiajs/vue3";
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { formatDistance, parseISO } from "date-fns";
import { relativeDate } from "@/Utils/date";
import PageHeading from "@/Components/PageHeading.vue";
import Pill from "@/Components/Pill.vue";

defineProps(["posts", "topics", "selectedTopic"]);

const formatedDate = (post) => relativeDate(post.created_at);
</script>

<template>
    <AppLayout title="Posts">
        <Container>
            <div>
                <PageHeading v-text="selectedTopic ? selectedTopic.name : 'All Posts'" />
                <p v-if="selectedTopic" class="mt-1 text-sm text-gray-600">{{ selectedTopic.description }}</p>

                <menu class="flex pt-1 pb-2 mt-4 space-x-1 overflow-x-auto">
                    <li>
                        <Pill :href="route('posts.index')" :filled="!selectedTopic">
                            All Posts
                        </Pill>
                    </li>
                    <li v-for="topic in topics" :key="topic.id">
                        <Pill :href="route('posts.index', { topic: topic.slug })"
                            :filled="topic.id === selectedTopic?.id">
                            {{ topic.name }}
                        </Pill>
                    </li>
                </menu>
            </div>

            <ul class="mt-4 divide-y">
                <li v-for="post in posts.data" :key="post.id"
                    class="flex flex-col items-baseline justify-between md:flex-row">
                    <Link :href="post.routes.show" class="block px-2 py-4 group">
                    <span class="text-lg font-bold group-hover:text-indigo-500">{{ post.title }}</span>
                    <span class="block mt-1 text-sm text-gray-600">
                        {{ formatedDate(post) }} by {{ post.user.name }}
                    </span>
                    </Link>

                    <Pill :href="route('posts.index', { topic: post.topic.slug })">
                        {{ post.topic.name }}
                    </Pill>
                </li>
            </ul>

            <Pagination :meta="posts.meta" />
        </Container>
    </AppLayout>
</template>
