<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/20/solid";
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    meta: {
        type: Object,
        required: true,
    },
    only: {
        type: Array,
        default: () => [],
    },
});

const only = computed(() => {
    return props.only.length === 0 ? [] : [...props.only, 'jetstream'];
});

const previewsUrl = computed(() => props.meta.links[0].url);
const nextUrl = computed(() => [...props.meta.links].reverse()[0].url);
</script>

<template>
    <div class="flex items-center justify-between px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex justify-between flex-1 sm:hidden">
            <Link :href="previewsUrl" :only="only" v-if="previewsUrl"
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
            Previous
            </Link>
            <Link :href="nextUrl" :only="only" v-if="nextUrl"
                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
            Next
            </Link>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    {{ " " }}
                    <span class="font-medium">{{ meta.from }}</span>
                    {{ " " }}
                    to
                    {{ " " }}
                    <span class="font-medium">{{ meta.to }}</span>
                    {{ " " }}
                    of
                    {{ " " }}
                    <span class="font-medium">{{ meta.total }}</span>
                    {{ " " }}
                    results
                </p>
            </div>
            <div>
                <nav class="inline-flex -space-x-px bg-white rounded-md shadow-sm isolate" aria-label="Pagination">
                    <Link v-for="link in meta.links" :href="link.url ? link.url : ''" :only="only"
                        class="relative inline-flex items-center px-3 py-2 text-gray-400 first-of-type:rounded-l-md last-of-type:rounded-r-md ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                        :class="{
                            'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600':
                                link.active,
                            'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0':
                                !link.active,
                        }" v-html="link.label" preserve-scroll>
                    </Link>
                </nav>
            </div>
        </div>
    </div>
</template>
