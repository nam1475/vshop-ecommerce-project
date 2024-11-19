<script setup>
import ProductList from '@/Pages/Customer/Components/ProductList.vue'
import Slider from '@/Pages/Customer/Components/Slider.vue'
import Main from '@/Pages/Customer/Components/Layout/Main.vue'  
import { computed, defineProps } from 'vue'
import { usePage, Head } from '@inertiajs/vue3';

const props = defineProps({
  products: Object,
  title: String
});

const categories = computed(() => usePage().props.categories);

</script>

<template>
<Main>
  <Head :title="title" />

  <!-- Slider -->
  <Slider :categories="categories"/>
  
  <!-- Content -->
  <div class="mx-auto my-10 max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-manrope font-bold text-4xl text-black mb-8 max-lg:text-center">
                Product list
            </h2>
  <ProductList :products="products" />
  </div>
</Main>

</template>