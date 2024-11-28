<script setup>
import Sidebar from '@/Components/Sidebar.vue';
import Alert  from '@/Components/Alert.vue';
import { useDataStore } from '@/stores/dataStore';
import { computed } from 'vue';

const dataStore = useDataStore()

const message = computed(() => dataStore.alertMessage)
const type = computed(() => dataStore.alertType)
</script>

<template>
    <div class="flex flex-col h-screen">
        <div class="relative flex flex-1">
            <Sidebar class="w-64 h-screen fixed bg-gray-800 space-y-4" />
            <main class="ml-64 flex-1 flex-col">
                <div class="flex-1 h-full p-8 overflow-y-auto bg-gray-100">
                    <div class="my-4">
                        <Alert :message="message" :type="type" />
                    </div>
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
