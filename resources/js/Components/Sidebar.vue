<template>
    <aside class="w-64 h-screen fixed bg-gray-800 space-y-4">
        <nav class="flex flex-col h-full">
            <Link :href="route('dashboard')">
                <div class="bg-black py-8 px-4">
                    <img src="/logo.png" class="w-24 mb-4" alt="Logo Hutama Karya">
                    <h1 class="font-bold text-xl text-white">Room Booking Management System</h1>
                </div>
            </Link>
            <div class="flex flex-1 flex-col">
                <ul class="p-4 flex-1">
                    <li class="my-2">
                        <Link
                            :href="route('schedule')"
                            :class="getMenuClass('schedule')">
                            Jadwal Rapat
                        </Link>
                    </li>
                    <li class="my-2">
                        <Link
                            href="/booking"
                            :class="getMenuClass('booking')">
                            Booking
                        </Link>
                    </li>
                    <template v-if="isAdmin($page.props.auth.user.role)">
                        <li class="my-2">
                            <button
                                @click="dataStore.toggleSidebarDropdown"
                                class="block w-full text-left py-2 px-4 rounded hover:bg-gray-700 cursor-pointer text-gray-300"
                            >
                                Master Data
                                <svg
                                    :class="{'rotate-180': isSidebarDropdownOpen, 'rotate-0': !isSidebarDropdownOpen}"
                                    class="inline-block w-4 h-4 transition-transform transform ml-2"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul v-if="isSidebarDropdownOpen" class="pl-4 mt-2 space-y-2">
                                <li>
                                    <Link
                                        :href="route('organizations.index')"
                                        :class="getMenuClass('organizations')">
                                        Organization
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        :href="route('users.index')"
                                        :class="getMenuClass('users')">
                                        User
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        :href="route('rooms.index')"
                                        :class="getMenuClass('rooms')">
                                        Rooms
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        :href="route('books.index')"
                                        :class="getMenuClass('books')">
                                        Books
                                    </Link>
                                </li>
                            </ul>
                        </li>
                        <li class="my-2">
                            <Link
                                href="/activity-log"
                                :class="getMenuClass('activity-log')">
                                Activity Log
                            </Link>
                        </li>
                    </template>
                </ul>
                <div class="h-24 px-4 py-4 w-full">
                    <ResponsiveNavLink
                        class="text-black hover:bg-transparent pe-0 py-0"
                        :href="route('logout')" method="post" as="button">
                    <div
                        @mouseenter="isHovered = true"
                        @mouseleave="isHovered = false"
                        class="p-4 flex justify-between items-center cursor-pointer hover:bg-red-500 hover:text-white rounded-lg w-full bg-gray-400 text-left"
                    >
                        <span>{{ isHovered ? 'Logout' : $page.props.auth.user.name }}</span>
                        <svg
                            class="inline-block w-4 h-4 transition-transform transform ml-2 rotate-270"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    </ResponsiveNavLink>
                </div>
            </div>
        </nav>
    </aside>
</template>

<script setup>
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { useDataStore } from '@/stores/dataStore';
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// State
const dataStore = useDataStore();
const isHovered = ref(false);
const isSidebarDropdownOpen = computed(() => dataStore.isSidebarDropdownOpen);

const isAdmin = (role) => {
    if (role == 'admin' || role == 'superadmin') return true
    return false
}

// Helper Function for Menu Class
const getMenuClass = (routeName) => {
    return route().current().includes(routeName)
        ? 'block py-2 px-4 rounded bg-gray-700 text-white'
        : 'block py-2 px-4 rounded hover:bg-gray-700 cursor-pointer text-gray-300';
};
</script>
