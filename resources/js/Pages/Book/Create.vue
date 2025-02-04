<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useDataStore } from '@/stores/dataStore';
import { Head, Link, useForm } from '@inertiajs/vue3';

// Define form state using Inertia's useForm
const form = useForm({
    title: '',
    room: '',
    startDate: '',
    endDate: '',
    startHour: '',
    finishHour: '',
    notes: '',
    isMultipleDate: false,
});

const props = defineProps({
    rooms: {
        type: Object,
        default: {
            data: []
        },
    },
});

const dataStore = useDataStore()

// Function to handle form submission
const submit = () => {
    form.post(route('rooms.store'), {
        onSuccess: () => {
            dataStore.setAlertSuccess('New room created successfully!')
        },
        onError: (error) => {
            console.error("Room creation error: ", error)
            dataStore.setAlertError("Failed to create new room!")
        }
    });
};
</script>

<template>
    <Head title="Room Master Data" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-8 text-gray-900">
                    <div class="mb-8">
                        <p class="mb-2 text-sm">
                            <Link class="text-blue-500" :href="route('books.index')">Booking</Link> / 
                            <span class="text-gray-500">Tambah</span>
                        </p>
                        <h1 class="font-semibold text-xl">Booking Ruang Rapat</h1>
                    </div>
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <label for="title" class="block mb-2 font-medium text-gray-500">Nama Rapat</label>
                            <input
                                type="text"
                                id="title"
                                v-model="form.title"
                                class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                :class="{ 'border-red-500': form.errors.title }"
                                required
                            />
                            <span v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</span>
                        </div>
                        <div class="w-1/2">
                            <div class="mb-4">
                                <label for="room" class="block mb-2 font-medium text-gray-500">Ruangan</label>
                                <select 
                                    class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                    :class="{ 'border-red-500': form.errors.room }"
                                    v-model="form.room"
                                    name="room"
                                    id="room"
                                    required
                                >
                                    <option value="" selected disabled class="text-gray-400">--- Pilih ruangan ---</option>
                                    <option v-for="room in rooms" :key="room.id" :value="room.id">{{ room.name }}</option>
                                </select>
                                <span v-if="form.errors.room" class="text-red-500 text-sm">{{ form.errors.room }}</span>
                            </div>
                        </div>
                        <div class="flex gap-x-4">
                            <div class="w-1/2 mb-4">
                                <label for="startDate" class="block mb-2 font-medium text-gray-500">Tanggal Mulai</label>
                                <input
                                    type="date"
                                    id="startDate"
                                    v-model="form.startDate"
                                    class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                    :class="{ 'border-red-500': form.errors.startDate }"
                                    required
                                />
                                <span v-if="form.errors.startDate" class="text-red-500 text-sm">{{ form.errors.startDate }}</span>
                            </div>
                            <div v-if="form.isMultipleDate" class="w-1/2 mb-4">
                                <label for="endDate" class="block mb-2 font-medium text-gray-500">Tanggal Selesai</label>
                                <input
                                    type="date"
                                    id="endDate"
                                    v-model="form.endDate"
                                    class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                    :class="{ 'border-red-500': form.errors.endDate }"
                                    required
                                />
                                <span v-if="form.errors.endDate" class="text-red-500 text-sm">{{ form.errors.endDate }}</span>
                            </div>
                        </div>
                        <div class="mb-4 flex items-center">
                            <input
                                type="checkbox"
                                id="isMultipleDate"
                                v-model="form.isMultipleDate"
                                class="rounded p-2 mr-2 outline-0 border-2 border-gray-200"
                            />
                            <label for="isMultipleDate" class="font-medium text-gray-500">Lebih dari satu hari</label>
                        </div>
                        <div class="flex gap-x-4">
                            <div class="w-1/2 mb-4">
                                <label for="startHour" class="block mb-2 font-medium text-gray-500">Jam Mulai</label>
                                <input
                                    type="time"
                                    id="startHour"
                                    v-model="form.startHour"
                                    class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                    :class="{ 'border-red-500': form.errors.startHour }"
                                    required
                                />
                                <span v-if="form.errors.startHour" class="text-red-500 text-sm">{{ form.errors.startHour }}</span>
                            </div>
                            <div class="w-1/2 mb-4">
                                <label for="finishHour" class="block mb-2 font-medium text-gray-500">Jam Selesai</label>
                                <input
                                    type="time"
                                    id="finishHour"
                                    v-model="form.finishHour"
                                    class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                    :class="{ 'border-red-500': form.errors.finishHour }"
                                    required
                                />
                                <span v-if="form.errors.finishHour" class="text-red-500 text-sm">{{ form.errors.finishHour }}</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="notes" class="block mb-2 font-medium text-gray-500">Catatan</label>
                            <textarea
                                id="notes"
                                v-model="form.notes"
                                class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                :class="{ 'border-red-500': form.errors.notes }"
                                required
                            ></textarea>
                            <span v-if="form.errors.notes" class="text-red-500 text-sm">{{ form.errors.notes }}</span>
                        </div>
                        <div class="mb-4 mt-8 flex">
                            <button
                                type="submit"
                                class="bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg px-8 py-2"
                                :disabled="form.processing"
                            >
                                {{ form.processing ? 'Memesan...' : 'Pesan Ruangan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
