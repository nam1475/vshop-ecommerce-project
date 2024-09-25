<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import Navbar from '@/Pages/Admin/Components/Layout/Navbar.vue'
import Sidebar from '@/Pages/Admin/Components/Layout/Sidebar.vue'
import {success, error} from "@/alert.js";
import { usePage } from '@inertiajs/vue3';

const page = usePage();
// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
    success(page);
    error(page);
});


</script>

<template>
    <div class="antialiased bg-gray-50">
        <!-- Navbar -->
        <Navbar  />

        <!-- Sidebar -->
        <Sidebar  />

        <!-- Content -->
        <main class="p-4 ml-64 h-screen pt-20">
            <slot />
        </main>
    </div>
</template>