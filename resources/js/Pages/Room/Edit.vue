<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useDataStore } from '@/stores/dataStore';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    room: {
        type: Object,
        default: {
            name: '',
            floor: '',
            capacity: 0,
        }
    }
})

// Define form state using Inertia's useForm
const form = useForm({
    name: props.room.name,
    floor: props.room.floor,
    capacity: props.room.capacity
});

const dataStore = useDataStore()

// Function to handle form submission
const submit = () => {
    form.patch(route('rooms.update', props.room.id), {
        onSuccess: () => {
            dataStore.setAlertSuccess("The room updated successfully!")
        },
        onError: (errors) => {
            dataStore.setAlertError("Failed to update the room!")
        }
    });
};
</script>

<template>
    <Head title="Room Master Data" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="w-1/2 mx-auto">
                        <div class="mb-8">
                            <p class="mb-2 text-sm">
                                <Link class="text-blue-500" href="/rooms">Rooms</Link> / 
                                <span class="text-gray-500">Edit</span>
                            </p>
                            <h1 class="font-semibold text-xl">Edit Existing Room</h1>
                        </div>
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="name" class="block mb-2 font-medium text-gray-500">Name</label>
                                <input
                                    type="text"
                                    id="name"
                                    v-model="form.name"
                                    class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                    :class="{ 'border-red-500': form.errors.name }"
                                    required
                                />
                                <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
                            </div>
                            <div class="mb-4">
                                <label for="floor" class="block mb-2 font-medium text-gray-500">Floor</label>
                                <input
                                    type="number"
                                    id="floor"
                                    v-model="form.floor"
                                    class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                    :class="{ 'border-red-500': form.errors.floor }"
                                    required
                                />
                                <span v-if="form.errors.floor" class="text-red-500 text-sm">{{ form.errors.floor }}</span>
                            </div>
                            <div class="mb-4">
                                <label for="capacity" class="block mb-2 font-medium text-gray-500">Capacity</label>
                                <input
                                    type="number"
                                    id="capacity"
                                    v-model="form.capacity"
                                    class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                    :class="{ 'border-red-500': form.errors.capacity }"
                                    required
                                />
                                <span v-if="form.errors.capacity" class="text-red-500 text-sm">{{ form.errors.capacity }}</span>
                            </div>
                            <div class="mb-4 mt-8 flex justify-end">
                                <button
                                    type="submit"
                                    class="bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg px-4 py-2"
                                    :disabled="form.processing"
                                >
                                    {{ form.processing ? 'Updating...' : 'Update' }}
                                </button>
                            </div>
                        </form>
                    </div>                    
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
