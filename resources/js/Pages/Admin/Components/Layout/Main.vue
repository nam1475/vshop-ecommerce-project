<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import Navbar from '@/Pages/Admin/Components/Layout/Navbar.vue'
import Sidebar from '@/Pages/Admin/Components/Layout/Sidebar.vue'

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
});


</script>

<template>
    <div class="antialiased bg-gray-50 dark:bg-gray-900">
        <!-- Navbar -->
        <Navbar  />

        <!-- Sidebar -->
        <Sidebar  />

        <!-- Content -->
        <main class="p-4 md:ml-64 h-auto pt-20">
            <slot />
        </main>
    </div>
</template>