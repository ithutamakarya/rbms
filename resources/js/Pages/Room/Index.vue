<script setup>
import Modal from '@/Components/Modal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useDataStore } from '@/stores/dataStore';

const props = defineProps({
    rooms: {
        type: Object,
        default: {
            data: []
        },
    }
});

const dataStore = useDataStore()

const form = useForm({});

const isModalOpen = ref(false);
const selectedRoomId = ref(null);

const closeModal = () => {
    isModalOpen.value = false;
    selectedRoomId.value = null;
};

const openDeleteModal = (id) => {
    selectedRoomId.value = id;
    isModalOpen.value = true;
};

const handleDelete = () => {
    if (!selectedRoomId.value) return;

    form.delete(route('rooms.destroy', selectedRoomId.value), {
        onSuccess: () => {
            isModalOpen.value = false;
            selectedRoomId.value = null;
            dataStore.setAlertSuccess('The room deleted successfully')
        },
        onError: (error) => {
            console.error("Deletion failed:", error);
            dataStore.setAlertError('The room failed to delete')
        },
    });
};
</script>

<template>
    <Head title="Room Master Data" />

    <AuthenticatedLayout>
        <div>
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-gray-900">
                        <Modal :show="isModalOpen" @close="closeModal">
                            <div class="p-6">
                                <h2 class="text-gray-900">Are you sure you want to delete this room?</h2>
                                <div class="mt-6 flex justify-end gap-x-4">
                                    <button class="px-4 py-2 hover:bg-gray-100 duration-100 border rounded-lg" @click="closeModal">Cancel</button>
                                    <button
                                        class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg border border-red-700 duration-100 text-white"
                                        :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing"
                                        @click="handleDelete"
                                    >
                                        {{ form.processing ? 'Deleting...' : 'Delete' }}
                                    </button>
                                </div>
                            </div>
                        </Modal>
                        <div class="flex justify-between items-center mb-8">
                            <h1 class="font-semibold text-xl">Master Data: Room</h1>
                            <Link href="/rooms/create">
                                <button class="px-4 py-2 bg-blue-100 hover:bg-blue-200 duration-300 text-blue-700 rounded-lg">Tambah</button>
                            </Link>
                        </div>
                        <div class="w-full mb-8">
                            <table class="table-fixed w-full">
                                <thead>
                                    <tr>
                                        <th class="py-4 border-b-2 w-[120px]">#</th>
                                        <th class="py-4 border-b-2 text-left">Name</th>
                                        <th class="py-4 border-b-2 w-[160px]">Capacity</th>
                                        <th class="py-4 border-b-2 w-[160px]">Floor</th>
                                        <th class="py-4 border-b-2 w-[240px]">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="props.rooms.data.length == 0">
                                        <tr>
                                            <td class="text-center py-4 text-gray-500" colspan="5">
                                                There is no room data yet, add some
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr v-for="(room, index) in props.rooms.data" :key="index" >
                                            <td class="py-4 border-b-2 text-center">{{ index + 1 }}</td>
                                            <td class="py-4 border-b-2">{{ room.name }}</td>
                                            <td class="py-4 border-b-2 text-center">{{ room.capacity }} <span class="text-gray-400">person</span></td>
                                            <td class="py-4 border-b-2 text-center">{{ room.floor }}</td>
                                            <td class="py-4 border-b-2 text-center flex justify-center gap-x-4">
                                                <Link :href="route('rooms.edit', room.id)" class="text-blue-500 underline">Edit</Link>
                                                <p @click="openDeleteModal(room.id)" class="cursor-pointer text-blue-500 underline">Delete</p>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
