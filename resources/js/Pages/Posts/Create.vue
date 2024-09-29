<template>
    <AppLayout title="Create a Post">
        <Container>
            <PageHeading>Create Post</PageHeading>

            <form @submit.prevent="createPost" class="mt-6">
                <div>
                    <InputLabel for="title" class="sr-only">Title</InputLabel>
                    <TextInput id="title" class="w-full" v-model="form.title" placeholder="Title..." />
                    <InputError :message="form.errors.title" class="mt-1" />
                </div>

                <div class="mt-3">
                    <InputLabel for="topic_id">Topic</InputLabel>
                    <select v-model="form.topic_id" id="topic_id" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option v-for="topic in topics" :value="topic.id" :key="topic.id">
                            {{ topic.name }}
                        </option>
                    </select>
                    <InputError :message="form.errors.topic_id" class="mt-1" />
                </div>

                <div class="mt-3">
                    <InputLabel for="body" class="sr-only">Body</InputLabel>
                    <MarkdownEditor v-model="form.body" editorClass="min-h-[450px]">
                        <template #toolbar="{ editor }">
                            <li v-if="!isInProduction()">
                                <button @click="autoFill" type="button" class="px-3 py-2" title="Autofill"><i
                                        class="ri-article-line"></i></button>
                            </li>
                        </template>
                    </MarkdownEditor>
                    <InputError :message="form.errors.body" class="mt-1" />
                </div>

                <div class="mt-3">
                    <PrimaryButton type="submit">Create Post</PrimaryButton>
                </div>
            </form>

        </Container>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import Container from "@/Components/Container.vue";
import MarkdownEditor from "@/Components/MarkdownEditor.vue";
import axios from "axios";
import { isInProduction } from "@/Utils/environment";
import PageHeading from "@/Components/PageHeading.vue";


const props = defineProps(['topics']);

const form = useForm({
    title: "",
    topic_id: props.topics[0].id,
    body: "Hello, world!",
});

const createPost = () => {
    form.post(route("posts.store"));
};

/**
 * Asynchronously autofills form fields with relevant data.
 * This function fetches necessary data and populates the form fields
 * to assist the user in creating a new post more efficiently.
 */
const autoFill = async () => {
    if (isInProduction()) {
        return;
    }

    const response = await axios.get("/local/post-content")

    form.title = response.data.title;
    form.body = response.data.body;
};

</script>
