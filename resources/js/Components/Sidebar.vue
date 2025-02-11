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
                    <template v-if="isAdmin">
                        <li class="my-2">
                            <Link
                                :href="route('manage-books.index')"
                                :class="getMenuClass('manage-books')">
                                Kelola Booking
                            </Link>
                        </li>
                    </template>
                    <template v-else>
                        <li class="my-2">
                            <Link
                                :href="route('books.index')"
                                :class="getMenuClass('books')">
                                Booking
                            </Link>
                        </li>
                    </template>
                    <template v-if="isAdmin">
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
                                        Divisi
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        :href="route('users.index')"
                                        :class="getMenuClass('users')">
                                        Pengguna
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        :href="route('rooms.index')"
                                        :class="getMenuClass('rooms')">
                                        Ruangan
                                    </Link>
                                </li>
                                <!-- <li>
                                    <Link
                                        :href="route('books.index')"
                                        :class="getMenuClass('books')">
                                        Data Booking
                                    </Link>
                                </li> -->
                            </ul>
                        </li>
                        <li class="my-2">
                            <Link
                                href="/activity-log"
                                :class="getMenuClass('activity-log')">
                                Log Aktivitas
                            </Link>
                        </li>
                    </template>
                    <li v-if="$page.props?.auth?.user"class="my-2">
                        <Link
                            :href="route('profile.edit')"
                            :class="getMenuClass('profile')">
                            Profil
                        </Link>
                    </li>
                </ul>
                <div class="h-24 px-4 py-4 w-full">
                    <template v-if="$page.props?.auth?.user">
                        <ResponsiveNavLink
                            class="text-black hover:bg-transparent pe-0 py-0"
                            :href="route('logout')" method="post" as="button">
                            <div
                                @mouseenter="isHovered = true"
                                @mouseleave="isHovered = false"
                                class="p-4 flex justify-between items-center cursor-pointer hover:bg-red-500 hover:text-white rounded-lg w-full bg-gray-400 text-left"
                            >
                                <span>{{ isHovered ? 'Keluar' : displayName }}</span>
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
                    </template>
                    <template v-else>
                        <ResponsiveNavLink
                            class="text-black hover:bg-transparent pe-0 py-0"
                            :href="route('login')" as="button">
                            <div
                                @mouseenter="isHovered = true"
                                @mouseleave="isHovered = false"
                                class="p-4 flex justify-between items-center cursor-pointer hover:bg-green-600 hover:text-white rounded-lg w-full bg-gray-400 text-left"
                            >
                                <span>Login</span>
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
                    </template>
                </div>
            </div>
        </nav>
    </aside>
</template>

<script setup>
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { useDataStore } from '@/stores/dataStore';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

// State
const dataStore = useDataStore();
const isHovered = ref(false);
const isSidebarDropdownOpen = computed(() => dataStore.isSidebarDropdownOpen);
const { props } = usePage()

const fullName = ref(props.auth?.user?.name || '')
const displayName = computed(() => fullName.value.length > 14 ? trimString(fullName.value) : fullName.value)

const isAdmin = computed(() => {
    const role = props.auth?.user?.role || '';
    return role === 'administrator' || role === 'superadmin';
})

function trimString(str) {
  if (str.length > 14) {
    let trimmed = str.slice(0, 14);
    if (trimmed[13] === ' ') {
      trimmed = trimmed.trimEnd();
    }
    return `${trimmed}...`;
  }
  return str;
}

// Helper Function for Menu Class
const getMenuClass = (routeName) => {
    return route().current().includes(routeName)
        ? 'block py-2 px-4 rounded bg-gray-700 text-white'
        : 'block py-2 px-4 rounded hover:bg-gray-700 cursor-pointer text-gray-300';
};
</script>
