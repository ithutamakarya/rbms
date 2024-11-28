<template>
    <aside class="w-64 h-screen fixed bg-gray-800 space-y-4">
        <nav class="flex flex-col h-full">
            <div class="bg-black py-8 px-4">
                <img src="/logo.png" class="w-24 mb-4" alt="Logo Hutama Karya">
                <h1 class="font-bold text-xl text-white">Room Booking Management System</h1>
            </div>
            <div class="flex flex-1 flex-col">
                <ul class="p-4 flex-1">
                    <li class="my-2">
                        <router-link class='block py-2 px-4 rounded hover:bg-gray-700 cursor-pointer text-gray-300'>
                            Jadwal Rapat
                        </router-link>
                    </li>
                    <li class="my-2">
                        <router-link class='block py-2 px-4 rounded hover:bg-gray-700 cursor-pointer text-gray-300'>
                            Booking
                        </router-link>
                    </li>
                    <li class="my-2">
                        <button
                            @click="toggleDropdown"
                            class="block w-full text-left py-2 px-4 rounded hover:bg-gray-700 cursor-pointer text-gray-300"
                        >
                            Master Data
                            <svg
                                :class="{'rotate-180': isDropdownOpen, 'rotate-0': !isDropdownOpen}"
                                class="inline-block w-4 h-4 transition-transform transform ml-2"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul v-if="isDropdownOpen" class="pl-4 mt-2 space-y-2">
                            <li>
                                <router-link to="/master-data/resources" class="block py-2 px-4 rounded hover:bg-gray-700 cursor-pointer text-gray-300">
                                    Organization
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/master-data/resources" class="block py-2 px-4 rounded hover:bg-gray-700 cursor-pointer text-gray-300">
                                    User
                                </router-link>
                            </li>
                            <li>
                                <Link href="/rooms" class="block py-2 px-4 rounded hover:bg-gray-700 cursor-pointer text-gray-300">
                                    Rooms
                                </Link>
                            </li>
                            <li>
                                <router-link to="/master-data/rooms" class="block py-2 px-4 rounded hover:bg-gray-700 cursor-pointer text-gray-300">
                                    Books
                                </router-link>
                            </li>
                        </ul>
                    </li>
                    <li class="my-2">
                        <router-link class='block py-2 px-4 rounded hover:bg-gray-700 cursor-pointer text-gray-300'>
                            Activity Log
                        </router-link>
                    </li>
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
                        <span>{{ isHovered ? 'Logout' : 'Administrator' }}</span>
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

<script>
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

export default {
    data() {
        return {
            isDropdownOpen: false,
            isHovered: false,
        };
    },
    methods: {
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        },
    },
    components: {
        ResponsiveNavLink,
        Link
    }
};
</script>