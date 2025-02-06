<script setup>
import Modal from '@/Components/Modal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useDataStore } from '@/stores/dataStore';

const props = defineProps({
    books: {
        type: Object,
        default: {
            data: []
        },
    }
});

const dataStore = useDataStore()

const form = useForm({});

const isModalOpen = ref(false);
const selectedBookId = ref(null);

const closeModal = () => {
    isModalOpen.value = false;
    selectedBookId.value = null;
};

const openDeleteModal = (id) => {
    selectedBookId.value = id;
    isModalOpen.value = true;
};

const handleDelete = () => {
    if (!selectedBookId.value) return;

    form.delete(route('books.destroy', selectedBookId.value), {
        onSuccess: () => {
            isModalOpen.value = false;
            selectedBookId.value = null;
            dataStore.setAlertSuccess('Berhasil menghapus pesanan ruangan!')
        },
        onError: (error) => {
            console.error("Deletion failed:", error);
            dataStore.setAlertError('Gagal menghapus pesanan ruangan!')
        },
    });
};
</script>

<template>
    <Head title="Manajemen Booking" />

    <AuthenticatedLayout>
        <div>
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-gray-900">
                        <Modal :show="isModalOpen" @close="closeModal">
                            <div class="p-6">
                                <h2 class="text-gray-900">Apakah anda yakin ingin menghapus ruangan ini?</h2>
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
                            <h1 class="font-semibold text-xl">Booking Ruang Rapat</h1>
                            <Link :href="route('books.create')">
                                <button class="px-4 py-2 bg-blue-100 hover:bg-blue-200 duration-300 text-blue-700 rounded-lg">Tambah</button>
                            </Link>
                        </div>
                        <div class="w-full mb-8">
                            <table class="table-fixed w-full">
                                <thead>
                                    <tr>
                                        <th class="py-4 border-b-2 w-[120px]">#</th>
                                        <th class="py-4 border-b-2 w-[360px] text-left">Nama Rapat</th>
                                        <th class="py-4 border-b-2">Ruang</th>
                                        <th class="py-4 border-b-2">Tanggal</th>
                                        <th class="py-4 border-b-2">Status</th>
                                        <th class="py-4 border-b-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="props.books.length == 0">
                                        <tr>
                                            <td class="text-center py-4 text-gray-500" colspan="7">
                                                Anda belum pernah melakukan booking ruangan.
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr v-for="(book, index) in props.books" :key="index" >
                                            <td class="py-4 border-b-2 text-center">{{ index + 1 }}</td>
                                            <td class="py-4 border-b-2">{{ book.title }}</td>
                                            <td class="py-4 border-b-2 text-center">{{ book.room.name }}</td>
                                            <td class="py-4 border-b-2 text-center">{{ book.start_date }}</td>
                                            <td class="py-4 border-b-2 text-center">{{ book.status }}</td>
                                            <td class="py-4 border-b-2 text-center flex justify-center gap-x-4">
                                                <Link :href="route('books.edit', book.id)" class="text-blue-500 underline">Edit</Link>
                                                <p @click="openDeleteModal(book.id)" class="cursor-pointer text-blue-500 underline">Hapus</p>
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
