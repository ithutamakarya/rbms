<script setup>
import Modal from '@/Components/Modal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useDataStore } from '@/stores/dataStore';

const props = defineProps({
    users: {
        type: Object,
        default: {
            data: []
        },
    }
});

const dataStore = useDataStore()

const form = useForm({});

const isModalOpen = ref(false);
const selectedUserId = ref(null);

const closeModal = () => {
    isModalOpen.value = false;
    selectedUserId.value = null;
};

const openDeleteModal = (id) => {
    selectedUserId.value = id;
    isModalOpen.value = true;
};

const handleDelete = () => {
    if (!selectedUserId.value) return;

    form.delete(route('users.destroy', selectedUserId.value), {
        onSuccess: () => {
            isModalOpen.value = false;
            selectedUserId.value = null;
            dataStore.setAlertSuccess('The user deleted successfully')
        },
        onError: (error) => {
            console.error("Deletion failed:", error);
            dataStore.setAlertError('The user failed to delete')
        },
    });
};
</script>

<template>
    <Head title="Master Data User" />

    <AuthenticatedLayout>
        <div>
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-gray-900">
                        <Modal :show="isModalOpen" @close="closeModal">
                            <div class="p-6">
                                <h2 class="text-gray-900">Apakah anda yakin ingin menghapus pengguna ini?</h2>
                                <div class="mt-6 flex justify-end gap-x-4">
                                    <button class="px-4 py-2 hover:bg-gray-100 duration-100 border rounded-lg" @click="closeModal">Batal</button>
                                    <button
                                        class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg border border-red-700 duration-100 text-white"
                                        :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing"
                                        @click="handleDelete"
                                    >
                                        {{ form.processing ? 'Menghapus...' : 'Hapus' }}
                                    </button>
                                </div>
                            </div>
                        </Modal>
                        <div class="flex justify-between items-center mb-8">
                            <h1 class="font-semibold text-xl">Master Data: Pengguna</h1>
                        </div>
                        <div class="w-full mb-8">
                            <table class="table-fixed w-full">
                                <thead>
                                    <tr>
                                        <th class="py-4 border-b-2 w-[120px]">#</th>
                                        <th class="py-4 border-b-2 text-left">Nama</th>
                                        <th class="py-4 border-b-2 w-[160px]">Divisi</th>
                                        <th class="py-4 border-b-2 w-[160px]">Role</th>
                                        <th class="py-4 border-b-2 w-[240px]">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="props.users.data.length == 0">
                                        <tr>
                                            <td class="text-center py-4 text-gray-500" colspan="5">
                                                Belum ada data pengguna sama sekali, tambahkan beberapa
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr v-for="(user, index) in props.users.data" :key="index" >
                                            <td class="py-4 border-b-2 text-center">{{ index + 1 }}</td>
                                            <td class="py-4 border-b-2">{{ user.name }}</td>
                                            <td class="py-4 border-b-2 text-center">{{ user.organization_id ? user.organization.name : 'not set' }}</td>
                                            <td class="py-4 border-b-2 text-center">{{ user.role }}</td>
                                            <td class="py-4 border-b-2 text-center flex justify-center gap-x-4">
                                                <Link :href="route('users.edit', user.id)" class="text-blue-500 underline">Edit</Link>
                                                <p @click="openDeleteModal(user.id)" class="cursor-pointer text-blue-500 underline">Hapus</p>
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
