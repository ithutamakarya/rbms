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
const isDetailModalOpen = ref(false);
const selectedBookId = ref(null);
const selectedBook = ref(null);

const closeModal = () => {
    isModalOpen.value = false;
    selectedBookId.value = null;
};

const openDeleteModal = (id) => {
    selectedBookId.value = id;
    isModalOpen.value = true;
};

const openDetailModal = (id) => {
    isDetailModalOpen.value = true;
    selectedBook.value = props.books.find(item => item.id === id);
}

const closeDetailModal = () => {
    isDetailModalOpen.value = false;
}

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

const formatStandardTime = (time) => {
    return time ? time.slice(0, 5) : '';
}
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
                                <h2 class="text-gray-900">Apakah anda yakin ingin menghapus data booking ruangan ini?</h2>
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
                        <Modal :show="isDetailModalOpen" @close="closeDetailModal">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-xl text-gray-900">Detail Pesanan</h2>
                                    <button class="px-4 py-2 hover:bg-gray-100 duration-100 rounded-full" @click="closeDetailModal">
                                        <font-awesome-icon icon="xmark" class="" />
                                    </button>
                                </div>
                                <table>
                                    <tr>
                                        <th class="text-left pr-8">Nama Rapat</th>
                                        <td class="py-1">: {{ selectedBook.title }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left pr-8">Ruang</th>
                                        <td class="py-1">: Lt. {{ selectedBook.room.floor }} {{ selectedBook.room.name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left pr-8">Tanggal</th>
                                        <td class="py-1">: {{ selectedBook.start_date }}<span v-if="selectedBook.end_date"><span class="text-gray-400"> s.d.</span> {{ selectedBook.end_date }}</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-left pr-8">Jam</th>
                                        <td class="py-1">: {{ formatStandardTime(selectedBook.start_hour) }} - {{ formatStandardTime(selectedBook.finish_hour) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left pr-8">Status</th>
                                        <td class="py-1">: {{ selectedBook.status }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left pr-8">Catatan</th>
                                        <td class="py-1">: {{ selectedBook.remarks }}</td>
                                    </tr>
                                </table>
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
                                        <th class="py-4 border-b-2 w-[60px]">#</th>
                                        <th class="py-4 border-b-2 text-left">Nama Rapat</th>
                                        <th class="py-4 border-b-2">Ruang</th>
                                        <th class="py-4 border-b-2">Jam</th>
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
                                            <td class="py-4 border-b-2 text-center">Lt. {{ book.room.floor }} {{ book.room.name }}</td>
                                            <td class="py-4 border-b-2 text-center">{{ book.start_date }}</td>
                                            <td class="py-4 border-b-2 text-center">{{ formatStandardTime(book.start_hour) }} - {{ formatStandardTime(book.finish_hour) }}</td>
                                            <td class="py-4 border-b-2 text-center">
                                                <font-awesome-icon
                                                    icon="circle-notch"
                                                    class="text-xs mr-1 "
                                                    :class="{
                                                    'text-green-500': book.status === 'approved',
                                                    'text-red-500': book.status === 'rejected',
                                                    'text-blue-500': book.status === 'pending'
                                                    }"
                                                /> {{ book.status }}
                                            </td>
                                            <td class="py-4 border-b-2 text-center flex justify-center gap-x-4">
                                                <template v-if="book.status == 'pending'">
                                                    <Link :href="route('books.edit', book.id)" class="text-blue-500 underline">Edit</Link>
                                                    <p @click="openDeleteModal(book.id)" class="cursor-pointer text-blue-500 underline">Hapus</p>
                                                </template>
                                                <template v-else>
                                                    <p @click="openDetailModal(book.id)" class="cursor-pointer text-blue-500 underline">Detail</p>
                                                </template>
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
