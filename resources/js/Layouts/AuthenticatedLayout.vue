<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="flex flex-col h-screen">
        <div class="relative flex flex-1">
            <Sidebar class="w-64 h-screen fixed bg-gray-800 space-y-4" />
            <main class="ml-64 flex-1 flex-col">
                <div class="flex-1 h-full p-8 overflow-y-auto bg-gray-100">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
