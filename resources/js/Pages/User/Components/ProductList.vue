<script setup>
import { defineProps } from 'vue'
import { router } from '@inertiajs/vue3'
import { success, error } from '@/alert.js';

defineProps({
  products: Array
});

function addToCart(product) {
  console.log(product);

  router.post(route('user.cart.store', product.id), {
    onSuccess: (page) => {
      success(page);
    },
    onError: (errors) => {
      error(errors);
    }
  });
}
</script>

<template>
  <div class="bg-white">
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
      <h2 class="sr-only">Products</h2>

      <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
        <a v-for="product in products" :key="product.id" href="#" class="group">
          <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-lg bg-gray-200 xl:aspect-h-8 xl:aspect-w-7">
            <img v-if="product.images.length > 0" :src="product.images[0].url" class="h-full w-full object-cover object-center group-hover:opacity-75" />
          </div>
          <h3 class="mt-4 text-sm text-gray-700">{{ product.name }}</h3>
          <div class="flex items-center justify-between mt-3">
            <p class="mt-1 text-lg font-medium text-gray-900">${{ product.price }}</p>
            <a @click="addToCart(product)" href="#" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
              Add to cart
            </a>
          </div>    
        </a>
      </div>
    </div>
  </div>
</template>