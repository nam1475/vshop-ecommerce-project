<script setup>
import { Link } from '@inertiajs/vue3'

</script>

<template>
  <Link
    :href="route('home')"
    class="flex items-center space-x-3 rtl:space-x-reverse"
  >
    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z"/>
    </svg>
    
    <span
      class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white"
      >VShop</span
    >
  </Link>


</template>
