<template>
    <div v-if="visible" :class="alertClass" role="alert">
        <span class="block sm:inline">{{ message }}</span>
    </div>
</template>

<script setup lang="ts">
import { useDataStore } from '@/stores/dataStore';
import { computed, watch } from 'vue';

const props = defineProps({ 
    message: {
        type: String,
        required: true
    },
    type: {
        type: String
    },
    duration: {
        type: Number,
        default: 3000
    }
 })

const dataStore = useDataStore()
 
const visible = computed(() => dataStore.alertVisibility)

const alertClass = computed(() => {
    return props.type === 'success' ?
    'fixed top-8 right-8 w-64 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4' :
    'fixed top-8 right-8 w-64 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4'
})

watch(visible, (newVal) => {
    if (newVal) {
        setTimeout(() => {
            dataStore.setAlertVisibility(false);
        }, props.duration);
    }
});
</script>