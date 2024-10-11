<script setup>
import { defineProps } from 'vue'
import { Link } from '@inertiajs/vue3'
import Pagination from '@/Pages/Admin/Components/Pagination.vue'
import { addToCart } from '@/addToCart.js';

defineProps({
  products: Object,
});


</script>

<template>
  <div class="bg-white mb-20">
    <div class="mx-auto max-w-2xl sm:mt-2 mb-20 lg:max-w-7xl lg:px-8">
      <h2 class="sr-only">Products</h2>

      <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
        <!-- <a v-for="product in products" :key="product.id" href="#" class="group"> -->
        <div v-for="product in products.data" :key="product.id" class="group">
          <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-lg bg-gray-200 xl:aspect-h-8 xl:aspect-w-7">
            <Link :href="route('customer.product.details', product.slug)">
              <img v-if="product.images.length" :src="product.images[0]" class="h-full w-full object-cover object-center group-hover:opacity-75" />
            </Link>
          </div>
          <h3 class="mt-4 text-sm text-gray-700">{{ product.name }}</h3>
          <div class="flex items-center justify-between mt-3">
            <p class="mt-1 text-lg font-medium text-gray-900">${{ product.price }}</p>
            <a @click="addToCart(product.id)" href="#" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
              Add to cart
            </a>
          </div>    
        </div>
      </div>
    </div>
    
    <!-- <div class="w-full text-center">
      <Link :href="route('customer.product.showMore')" class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">Show more</Link>
    </div> -->
    
    <div class="flex justify-center">
      <!-- <Pagination :links="products.links" /> -->
      <Pagination :links="products.meta.links" />
    </div>


  </div>
</template>