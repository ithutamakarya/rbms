<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useDataStore } from '@/stores/dataStore';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
});

const dataStore = useDataStore()

const submit = () => {
    form.post(route('organizations.store'), {
        onSuccess: () => {
            dataStore.setAlertSuccess('Berhasil menambahkan divisi baru!')
        },
        onError: (error) => {
            console.error("Organization creation error: ", error)
            dataStore.setAlertError("Gagal menambahkan divisi!")
        }
    });
};
</script>

<template>
    <Head title="Master Data Divisi" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-8 text-gray-900">
                    <div class="w-1/2">
                        <div class="mb-8">
                            <p class="mb-2 text-sm">
                                <Link class="text-blue-500" :href="route('organizations.index')">Divisi</Link> / 
                                <span class="text-gray-500">Tambah</span>
                            </p>
                            <h1 class="font-semibold text-xl">Tambah Divisi Baru</h1>
                        </div>
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="name" class="block mb-2 font-medium text-gray-500">Nama</label>
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
                            <div class="mb-4 mt-8 flex">
                                <button
                                    type="submit"
                                    class="bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg px-8 py-2"
                                    :disabled="form.processing"
                                >
                                    {{ form.processing ? 'Menambahkan...' : 'Tambah' }}
                                </button>
                            </div>
                        </form>
                    </div>                    
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
