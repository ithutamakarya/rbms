<template>
    <div v-if="links.length > 3" class="flex justify-center mt-4">
        <nav class="inline-flex space-x-1">
            <template v-for="(link, index) in links" :key="index">
                <button
                    v-if="link.url"
                    @click="goToPage(link.url)"
                    v-html="link.label"
                    :class="[
                        'px-4 py-2 border rounded-md transition',
                        link.active ? 'bg-blue-500 text-white' : 'bg-white hover:bg-gray-200 text-gray-700'
                    ]"
                ></button>
                <span v-else v-html="link.label" class="px-4 py-2 text-gray-500"></span>
            </template>
        </nav>
    </div>
</template>

<script setup>
import { defineProps } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    links: Array
});

const goToPage = (url) => {
    router.get(url, {}, { preserveState: true, preserveScroll: true });
};
</script>
