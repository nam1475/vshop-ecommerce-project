<script setup>
import ProductList from '@/Pages/Customer/Components/ProductList.vue'
import Slider from '@/Pages/Customer/Components/Slider.vue'
import Main from '@/Pages/Customer/Components/Layout/Main.vue'  
import { computed, defineProps } from 'vue'
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
  products: Object,
});

const categories = computed(() => usePage().props.categories);

</script>

<template>
<Main>
  <!-- Slider -->
  <Slider :categories="categories"/>
  
  <!-- Content -->
  <ProductList :products="products" />
</Main>

</template>