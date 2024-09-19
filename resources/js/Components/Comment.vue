<script setup>
import { relativeDate } from "@/Utils/date";

defineProps(['comment']);
</script>

<template>
    <div class="sm:flex">
        <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-4">
            <img :src="comment.user.profile_photo_url" alt="User profile photo" class="w-10 h-10 rounded-full" />
        </div>
        <div>
            <p class="mt-1">{{ comment.body }}</p>
            <span class="block mt-1 text-xs text-gray-600">By {{ comment.user.name }} {{
                relativeDate(comment.created_at) }} ago</span>
        </div>
    </div>
</template>
