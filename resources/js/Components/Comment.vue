<template>
    <div class="sm:flex">
        <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-4">
            <img :src="comment.user.profile_photo_url" alt="User profile photo" class="w-10 h-10 rounded-full" />
        </div>
        <div class="flex-1">
            <div class="mt-1 prose-sm prose max-w-none" v-html="comment.html"></div>
            <span class="block mt-1 text-xs text-gray-600">
                By {{ comment.user.name }} {{ relativeDate(comment.created_at) }} | <span class="text-pink-500">{{
                    comment.likes_count }} likes</span>
            </span>
            <div class="flex justify-end mt-1 space-x-3 empty:hidden">
                <div v-if="$page.props.auth.user">
                    <Link v-if="comment.can.like" preserve-scroll :href="route('likes.store', ['comment', comment.id])"
                        method="post" class="inline-block text-gray-700 transition-colors hover:text-pink-500">
                    <HandThumbUpIcon class="inline-block mr-1 size-4" />
                    <span class="sr-only">Like Comment</span>
                    </Link>
                    <Link v-else preserve-scroll :href="route('likes.destroy', ['comment', comment.id])" method="delete"
                        class="inline-block text-gray-700 transition-colors hover:text-pink-500">
                    <HandThumbDownIcon class="inline-block mr-1 size-4" />
                    <span class="sr-only">Unlike Comment</span>
                    </Link>
                </div>

                <form v-if="comment.can?.update" @submit.prevent="$emit('edit', comment.id)">
                    <button class="font-mono text-xs hover:font-semibold">Edit</button>
                </form>

                <form v-if="comment.can?.delete" @submit.prevent="$emit('delete', comment.id)">
                    <button class="font-mono text-xs text-red-700 hover:font-semibold">Delete</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { relativeDate } from "@/Utils/date";
import { HandThumbDownIcon, HandThumbUpIcon } from "@heroicons/vue/20/solid";
import { Link } from "@inertiajs/vue3";

const props = defineProps(['comment']);
const emit = defineEmits(['edit', 'delete']);

</script>
