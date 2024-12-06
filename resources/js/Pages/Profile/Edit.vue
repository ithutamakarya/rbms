<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useDataStore } from '@/stores/dataStore';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    organizations: {
        type: Object,
    }
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    division: user.organization_id || 0,
});

const changePasswordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const dataStore = useDataStore()

// Function to handle form submission
const submit = () => {
    form.patch(route('profile.update', user.id), {
        onSuccess: () => {
            dataStore.setAlertSuccess("Profile updated successfully!")
        },
        onError: (errors) => {
            dataStore.setAlertError("Failed to update profile!")
        }
    });
};

const updatePassword = () => {
    changePasswordForm.put(route('password.update', user.id), {
        preserveScroll: true,
        onSuccess: () => {
            changePasswordForm.reset();
            dataStore.setAlertSuccess('Your password updated successfully!');
        },
        onError: () => {
            dataStore.setAlertError('Failed to update your password!');
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
            }
            if (form.errors.current_password) {
                form.reset('current_password');
            }
        },
    });
};
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-8 text-gray-900">
                    <div class="w-1/2">
                        <div class="mb-8">
                            <h1 class="font-semibold text-xl">Edit User Profile</h1>
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
                                <label for="email" class="block mb-2 font-medium text-gray-500">Email</label>
                                <input
                                    type="text"
                                    id="email"
                                    v-model="form.email"
                                    class="w-full rounded-lg p-2 text-gray-500 bg-gray-100 outline-0 border-2 border-gray-200"
                                    :class="{ 'border-red-500': form.errors.email }"
                                    required
                                    disabled
                                />
                                <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.email }}</span>
                            </div>
                            <div class="mb-4">
                                <label for="division" class="block mb-2 font-medium text-gray-500">Division</label>
                                <select
                                    type="text"
                                    id="division"
                                    v-model="form.division"
                                    class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                    :class="{ 'border-red-500': form.errors.division }"
                                    required
                                >
                                    <option selected disabled value="0">Select division...</option>
                                    <option v-for="(division, index) in organizations" :key="index" :value="division.id">{{ division.name }}</option>
                                </select>
                                <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.division }}</span>
                            </div>
                            <div class="mb-4 mt-8 flex">
                                <button
                                    type="submit"
                                    class="bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg px-8 py-2"
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

        <div class="max-w-7xl mx-auto my-12">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-8 text-gray-900">
                    <div class="w-1/2">
                        <div class="mb-8">
                            <h1 class="font-semibold text-xl">Change User Password</h1>
                        </div>
                        <form @submit.prevent="updatePassword">
                            <div class="mb-4">
                                <label for="current_password" class="block mb-2 font-medium text-gray-500">Current Password</label>
                                <input
                                    type="password"
                                    id="current_password"
                                    v-model="changePasswordForm.current_password"
                                    class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                    :class="{ 'border-red-500': changePasswordForm.errors.current_password }"
                                    required
                                />
                                <span v-if="changePasswordForm.errors.current_password" class="text-red-500 text-sm">{{ changePasswordForm.errors.current_password }}</span>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="block mb-2 font-medium text-gray-500">New Password</label>
                                <input
                                    type="password"
                                    id="password"
                                    v-model="changePasswordForm.password"
                                    class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                    :class="{ 'border-red-500': changePasswordForm.errors.password }"
                                    required
                                />
                                <span v-if="changePasswordForm.errors.password" class="text-red-500 text-sm">{{ changePasswordForm.errors.password }}</span>
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation" class="block mb-2 font-medium text-gray-500">Confirm Password</label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    v-model="changePasswordForm.password_confirmation"
                                    class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                    :class="{ 'border-red-500': changePasswordForm.errors.password_confirmation }"
                                    required
                                />
                                <span v-if="changePasswordForm.errors.password_confirmation" class="text-red-500 text-sm">{{ changePasswordForm.errors.password_confirmation }}</span>
                            </div>
                            <div class="mb-4 mt-8 flex">
                                <button
                                    type="submit"
                                    class="bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg px-8 py-2"
                                    :disabled="changePasswordForm.processing"
                                >
                                    {{ changePasswordForm.processing ? 'Updating...' : 'Update' }}
                                </button>
                            </div>
                        </form>
                    </div>                    
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
