<script setup>
import { onMounted, defineProps } from 'vue'
import { initFlowbite } from 'flowbite'
import ProductList from '@/Pages/User/Components/ProductList.vue'
import Hero from '@/Pages/User/Components/Hero.vue'
import Main from '@/Pages/User/Components/Layout/Main.vue'  

defineProps({
  products: Array
})
onMounted(() => {
    initFlowbite();
});
</script>

<template>
<Main>
  <!-- Hero -->
  <Hero />
  
  <!-- Content -->
  <ProductList :products="products" />
</Main>

  <Footer />
</template>