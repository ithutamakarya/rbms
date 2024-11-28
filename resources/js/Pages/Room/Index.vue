<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    rooms: {
        type: Array,
        default: [],
    }
})
</script>

<template>
    <Head title="Room Master Data" />

    <AuthenticatedLayout>
        <div>
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-8">
                            <h1 class="font-semibold text-xl">Room Management</h1>
                            <Link href="/rooms/create">
                                <button class="px-4 py-2 bg-blue-100 hover:bg-blue-200 duration-300 text-blue-700 rounded-lg">Tambah</button>
                            </Link>
                        </div>
                        <div class="w-full">
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
                                            <td class="py-4 border-b-2 text-center">{{ room.capacity }}</td>
                                            <td class="py-4 border-b-2 text-center">{{ room.floor }}</td>
                                            <td class="py-4 border-b-2 text-center flex justify-center gap-x-4">
                                                <Link :href="`/rooms/${room.id}/edit`" class="text-blue-500 underline">Edit</Link>
                                                <Link :href="`/rooms/${room.id}/edit`" class="text-blue-500 underline">Delete</Link>
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
