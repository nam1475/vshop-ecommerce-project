<script setup>
import Navbar from '@/Pages/Customer/Components/Layout/Navbar.vue'
import Footer from '@/Pages/Customer/Components/Layout/Footer.vue'
import { initFlowbite } from 'flowbite'
import { onMounted } from 'vue'

onMounted(() => {
    initFlowbite();
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