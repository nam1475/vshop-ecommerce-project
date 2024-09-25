<script setup>
import Main from '@/Pages/Admin/Components/Layout/Main.vue';
import {defineProps} from "vue";

defineProps({
  title: String,
  action: Function
});

</script>

<template>
<Main>
    <section class="bg-white dark:bg-gray-900">
      <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
          {{ title }}
        </h2>
        <form
          class="p-4 md:p-5"
          @submit.prevent="action"
        >
        
          <slot />

          <button
            type="submit"
            class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
          >
            Submit
          </button>
        </form>
      </div>
    </section>
</Main>
</template>