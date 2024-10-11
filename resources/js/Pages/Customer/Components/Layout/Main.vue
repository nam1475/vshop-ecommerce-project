<script setup>
import Navbar from '@/Pages/Customer/Components/Layout/Navbar.vue'
import Footer from '@/Pages/Customer/Components/Layout/Footer.vue'
import { initFlowbite } from 'flowbite'
import { onMounted } from 'vue'
import {success, error} from "@/alert.js";
import { usePage } from '@inertiajs/vue3';

const page = usePage();
onMounted(() => {
    initFlowbite();
    success(page);
    error(page);
});
</script>

<template>
    
    <!-- Navbar -->
    <Navbar />

    <!-- Content -->
    <slot />
    
    <!-- Footer -->
    <Footer />
    
</template>