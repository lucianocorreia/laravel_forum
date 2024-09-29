<template>
    <div
        class="bg-white border-0 rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600">

        <menu class="flex border-b divide-x">
            <li>
                <button @click="editor.chain().focus().toggleBold().run()" type="button"
                    :class="{ 'bg-indigo-500 text-white': editor?.isActive('bold'), 'hover:bg-gray-200': !editor?.isActive('bold') }"
                    class="px-3 py-2 rounded-tl-md" title="Bold"><i class="ri-bold"></i></button>
            </li>
            <li>
                <button @click="editor.chain().focus().toggleItalic().run()" type="button"
                    :class="{ 'bg-indigo-500 text-white': editor?.isActive('italic'), 'hover:bg-gray-200': !editor?.isActive('italic') }"
                    class="px-3 py-2" title="Italic"><i class="ri-italic"></i></button>
            </li>
            <li>
                <button @click="editor.chain().focus().toggleStrike().run()" type="button"
                    :class="{ 'bg-indigo-500 text-white': editor?.isActive('strike'), 'hover:bg-gray-200': !editor?.isActive('strike') }"
                    class="px-3 py-2" title="Strikethrough"><i class="ri-strikethrough"></i></button>
            </li>
            <li>
                <button @click="editor.chain().focus().toggleBlockquote().run()" type="button"
                    :class="{ 'bg-indigo-500 text-white': editor?.isActive('blockquote'), 'hover:bg-gray-200': !editor?.isActive('blockquote') }"
                    class="px-3 py-2" title="Blockquote"><i class="ri-double-quotes-l"></i></button>
            </li>
            <li>
                <button @click="editor.chain().focus().toggleBulletList().run()" type="button"
                    :class="{ 'bg-indigo-500 text-white': editor?.isActive('bulletList'), 'hover:bg-gray-200': !editor?.isActive('bulletList') }"
                    class="px-3 py-2" title="Bullet List"><i class="ri-list-unordered"></i></button>
            </li>
            <li>
                <button @click="editor.chain().focus().toggleOrderedList().run()" type="button"
                    :class="{ 'bg-indigo-500 text-white': editor?.isActive('orderedList'), 'hover:bg-gray-200': !editor?.isActive('orderedList') }"
                    class="px-3 py-2" title="Numeric List"><i class="ri-list-ordered"></i></button>
            </li>
            <li>
                <button @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" type="button"
                    :class="{ 'bg-indigo-500 text-white': editor?.isActive('heading', { level: 2 }), 'hover:bg-gray-200': !editor?.isActive('heading', { level: 2 }) }"
                    class="px-3 py-2" title="H1"><i class="ri-h-1"></i></button>
            </li>
            <li>
                <button @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" type="button"
                    :class="{ 'bg-indigo-500 text-white': editor?.isActive('heading', { level: 3 }), 'hover:bg-gray-200': !editor?.isActive('heading', { level: 3 }) }"
                    class="px-3 py-2" title="H2"><i class="ri-h-2"></i></button>
            </li>
            <li>
                <button @click="editor.chain().focus().toggleHeading({ level: 4 }).run()" type="button"
                    :class="{ 'bg-indigo-500 text-white': editor?.isActive('heading', { level: 4 }), 'hover:bg-gray-200': !editor?.isActive('heading', { level: 4 }) }"
                    class="px-3 py-2" title="H3"><i class="ri-h-3"></i></button>
            </li>
            <li>
                <button @click="promptUserForHref" type="button"
                    :class="{ 'bg-indigo-500 text-white': editor?.isActive('link'), 'hover:bg-gray-200': !editor?.isActive('link') }"
                    class="px-3 py-2" title="Add Link"><i class="ri-link"></i></button>
            </li>
            <slot name="toolbar" :editor="editor"></slot>
        </menu>

        <EditorContent :editor="editor" />
    </div>

</template>

<script setup>
import StarterKit from "@tiptap/starter-kit";
import { EditorContent, useEditor } from "@tiptap/vue-3";
import { Markdown } from "tiptap-markdown";
import { onMounted, watch } from "vue";
import "remixicon/fonts/remixicon.css";
import Link from "@tiptap/extension-link";
import Placeholder from "@tiptap/extension-placeholder";

const props = defineProps({
    editorClass: '',
    placeholder: '',
});

const model = defineModel();


defineExpose({ focus: () => editor.value.commands.focus() });

const editor = useEditor({
    extensions: [
        StarterKit.configure({
            heading: {
                levels: [2, 3, 4],
            },
        }),
        Markdown,
        Link,
        Placeholder.configure({
            placeholder: props.placeholder,
        })
    ],
    editorProps: {
        attributes: {
            class: `prose prose-sm max-w-none py-1.5 px-3 ${props.editorClass}`,
        },
    },
    onUpdate: () => model.value = editor.value?.storage.markdown.getMarkdown(),
});

onMounted(() => {
    watch(
        model,
        (value) => {
            if (value == editor.value?.storage.markdown.getMarkdown()) return;

            editor.value?.commands.setContent(value);
        },
        { immediate: true }
    );
});

const promptUserForHref = () => {
    if (editor.value?.isActive('link')) {
        return editor.value?.chain().focus().unsetLink().run();
    }

    const href = window.prompt('Enter the URL');

    if (!href) return editor.value?.chain().focus().run();

    return editor.value?.chain().focus().setLink({ href }).run();
};

</script>

<style scoped>
:deep(.tiptap p.is-editor-empty:first-child::before) {
    color: #adb5bd;
    content: attr(data-placeholder);
    float: left;
    height: 0;
    pointer-events: none;
}
</style>
