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

const approveForm = useForm({});
const rejectForm = useForm({
    remark: ''
});

const isApproveModalOpen = ref(false);
const isRejectModalOpen = ref(false);
const selectedBookId = ref(null);

const closeApproveModal = () => {
    isApproveModalOpen.value = false;
    selectedBookId.value = null;
};

const closeRejectModal = () => {
    isRejectModalOpen.value = false;
    selectedBookId.value = null;
};

const openApproveModal = (id) => {
    selectedBookId.value = id;
    isApproveModalOpen.value = true;
};

const openRejectModal = (id) => {
    selectedBookId.value = id;
    isRejectModalOpen.value = true;
};

const handleApprove = () => {
    if (!selectedBookId.value) return;

    approveForm.patch(route('manage-books.approve', selectedBookId.value), {
        onSuccess: () => {
            isApproveModalOpen.value = false;
            selectedBookId.value = null;
            dataStore.setAlertSuccess('Berhasil menyetujui pesanan ruangan!')
        },
        onError: (error) => {
            isApproveModalOpen.value = false;
            console.error("Approval failed:", error);
            dataStore.setAlertError('Gagal menyetujui pesanan ruangan!')
        },
    });
};

const handleReject = () => {
    if (!selectedBookId.value) return;

    rejectForm.patch(route('manage-books.reject', selectedBookId.value), {
        onSuccess: () => {
            isRejectModalOpen.value = false;
            selectedBookId.value = null;
            dataStore.setAlertSuccess('Berhasil menolak pesanan ruangan!')
        },
        onError: (error) => {
            isRejectModalOpen.value = false;
            console.error("Rejection failed:", error);
            dataStore.setAlertError('Gagal menolak pesanan ruangan!')
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
                        <Modal :show="isApproveModalOpen" @close="closeApproveModal">
                            <div class="p-6">
                                <h2 class="text-gray-900">Apakah anda yakin ingin menyetujui pesanan ini?</h2>
                                <div class="mt-6 flex justify-end gap-x-4">
                                    <button class="px-4 py-2 hover:bg-gray-100 duration-100 border rounded-lg" @click="closeApproveModal">Batal</button>
                                    <button
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg border border-blue-700 duration-100 text-white"
                                        :class="{ 'opacity-25': approveForm.processing }"
                                        :disabled="approveForm.processing"
                                        @click="handleApprove"
                                    >
                                        {{ approveForm.processing ? 'Menyetujui...' : 'Konfirmasi' }}
                                    </button>
                                </div>
                            </div>
                        </Modal>
                        <Modal :show="isRejectModalOpen" @close="closeRejectModal">
                            <form @submit.prevent="handleReject">
                                <div class="p-6">
                                    <label for="remark" class="text-gray-900">Berikan catatan kepada pemesan <span class="text-gray-400">(remark)</span></label>
                                    <div class="my-4">
                                        <textarea
                                            name="remark"
                                            id="remark"
                                            v-model="rejectForm.remark"
                                            class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                        ></textarea>
                                    </div>
                                    <div class="mt-6 flex justify-end gap-x-4">
                                        <button class="px-4 py-2 hover:bg-gray-100 duration-100 border rounded-lg" @click="closeRejectModal">Batal</button>
                                        <button
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg border border-blue-700 duration-100 text-white"
                                            :class="{ 'opacity-25': rejectForm.processing }"
                                            :disabled="rejectForm.processing"
                                            type="submit"
                                        >
                                            {{ rejectForm.processing ? 'Menolak...' : 'Konfirmasi' }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </Modal>
                        <div class="flex justify-between items-center mb-8">
                            <h1 class="font-semibold text-xl">Kelola Booking Ruang Rapat</h1>
                        </div>
                        <div class="w-full mb-8">
                            <table class="table-fixed w-full">
                                <thead>
                                    <tr>
                                        <th class="py-4 border-b-2 w-[60px]">#</th>
                                        <th class="py-4 border-b-2 text-left">Nama Rapat</th>
                                        <th class="py-4 border-b-2">Ruang</th>
                                        <th class="py-4 border-b-2">Tanggal</th>
                                        <th class="py-4 border-b-2">Jam</th>
                                        <th class="py-4 border-b-2">PIC</th>
                                        <th class="py-4 border-b-2">Status</th>
                                        <th class="py-4 border-b-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="props.books.length == 0">
                                        <tr>
                                            <td class="text-center py-4 text-gray-500" colspan="7">
                                                Belum terdapat booking ruangan.
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr v-for="(book, index) in props.books" :key="index" >
                                            <td class="py-4 text-center">{{ index + 1 }}</td>
                                            <td class="py-4">{{ book.title }}</td>
                                            <td class="py-4 text-center">{{ book.room.name }}</td>
                                            <td v-if="book.end_date" class="py-4 text-center">{{ book.start_date }} s.d. {{ book.end_date }}</td>
                                            <td v-else class="py-4 text-center">{{ book.start_date }}</td>
                                            <td class="py-4 text-center">{{ formatStandardTime(book.start_hour) }} - {{ formatStandardTime(book.finish_hour) }}</td>
                                            <td class="py-4 text-center">{{ book.requester.name }} - {{ book.requester.organization.shortname }}</td>
                                            <td class="py-4 text-center">{{ book.status }}</td>
                                            <td v-if="book.status == 'pending'" class="py-4 text-center flex justify-center gap-x-2">
                                                <p @click="openApproveModal(book.id)" class="cursor-pointer text-green-500 border border-2 rounded-lg hover:border-green-700 hover:bg-green-600 duration-300 hover:text-white py-1 px-2">
                                                    <font-awesome-icon icon="check" class="text-xs mr-1" /> Setujui
                                                </p>
                                                <p @click="openRejectModal(book.id)" class="cursor-pointer text-red-500 border border-2 rounded-lg hover:border-red-700 hover:bg-red-600 duration-300 hover:text-white py-1 px-2">
                                                    <font-awesome-icon icon="xmark" class="text-xs mr-1" /> Tolak
                                                </p>
                                            </td>
                                            <td v-else class="py-4 text-center flex justify-center gap-x-4">
                                                <span>-</span>
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
