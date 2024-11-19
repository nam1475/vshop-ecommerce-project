<script setup>
import Navbar from '@/Pages/Customer/Components/Layout/Navbar.vue'
import Footer from '@/Pages/Customer/Components/Layout/Footer.vue'
import { initFlowbite } from 'flowbite'
import { computed, onMounted, watch } from 'vue'
// import {success, error} from "@/alert.js";
import { usePage } from '@inertiajs/vue3';
import Swal from "sweetalert2";

watch(() => usePage().props.flash, (newVal) => {
    if(newVal.success){
        Swal.fire({
            icon: "success",
            title: newVal.success,
            toast: true,
            position: "top-right",
            showConfirmButton: false,
            timer: 4000, 
            showCloseButton: true,
            timerProgressBar: true
        });
    }

    if(newVal.error){
        Swal.fire({
            icon: "error",
            title: newVal.error,
            toast: true,
            position: "top-right",
            showConfirmButton: false,
            timer: 4000, 
            showCloseButton: true,
            timerProgressBar: true
        });
    }
});

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