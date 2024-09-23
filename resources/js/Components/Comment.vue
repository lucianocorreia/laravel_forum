<template>
    <div class="sm:flex">
        <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-4">
            <img :src="comment.user.profile_photo_url" alt="User profile photo" class="w-10 h-10 rounded-full" />
        </div>
        <div class="flex-1">
            <p class="mt-1 break-all">{{ comment.body }}</p>
            <span class="block mt-1 text-xs text-gray-600">
                By {{ comment.user.name }} {{ relativeDate(comment.created_at) }} ago
            </span>
            <div class="flex justify-end mt-1 space-x-3 empty:hidden">
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

const props = defineProps(['comment']);
const emit = defineEmits(['edit', 'delete']);

</script>
