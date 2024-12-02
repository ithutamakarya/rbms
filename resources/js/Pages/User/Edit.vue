<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useDataStore } from '@/stores/dataStore';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: {
        type: Object
    },
    organizations: {
        type: Object,
    }
})

// Define form state using Inertia's useForm
const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role: props.user.role,
    division: props.user.organization_id || 0,
});

const changePasswordForm = useForm({
    password: '',
    password_confirmation: '',
});

const dataStore = useDataStore()

// Function to handle form submission
const submit = () => {
    form.patch(route('users.update', props.user.id), {
        onSuccess: () => {
            dataStore.setAlertSuccess("The user profile updated successfully!")
        },
        onError: (errors) => {
            dataStore.setAlertError("Failed to update the user profile!")
        }
    });
};

const updatePassword = () => {
    changePasswordForm.put(route('password.update_for_admin', props.user.id), {
        preserveScroll: true,
        onSuccess: () => {
            changePasswordForm.reset();
            dataStore.setAlertSuccess('User password updated successfully!');
        },
        onError: () => {
            changePasswordForm.reset();
            dataStore.setAlertError('Failed to update user password!');
        },
    });
};
</script>

<template>
    <Head title="Master Data user" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-8 text-gray-900">
                    <div class="w-1/2">
                        <div class="mb-8">
                            <p class="mb-2 text-sm">
                                <Link class="text-blue-500" :href="route('users.index')">User</Link> / 
                                <span class="text-gray-500">Edit</span>
                            </p>
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
                            <div class="mb-4">
                                <label for="role" class="block mb-2 font-medium text-gray-500">role</label>
                                <select
                                    type="text"
                                    id="role"
                                    v-model="form.role"
                                    class="w-full rounded-lg p-2 outline-0 border-2 border-gray-200"
                                    :class="{ 'border-red-500': form.errors.role }"
                                    required
                                >
                                    <option value="user">User</option>
                                    <option value="administrator">Administrator</option>
                                </select>
                                <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.role }}</span>
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
