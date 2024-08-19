<script setup>
import Navbar from '@/Pages/User/Components/Layout/Navbar.vue'
import Footer from '@/Pages/User/Components/Layout/Footer.vue'
</script>

<template>
    
    <!-- Navbar -->
    <Navbar />

    <!-- Content -->
    <slot />
    
    <!-- Footer -->
    <Footer />
    
</template>