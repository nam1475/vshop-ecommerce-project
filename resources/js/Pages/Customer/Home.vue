<script setup>
import ProductList from '@/Pages/Customer/Components/ProductList.vue'
import Hero from '@/Pages/Customer/Components/Hero.vue'
import Main from '@/Pages/Customer/Components/Layout/Main.vue'  
import { defineProps } from 'vue'

defineProps({
  products: Array
});

</script>

<template>
<Main>
  <!-- Hero -->
  <Hero />
  
  <!-- Content -->
  <ProductList :products="products" />
</Main>

</template>