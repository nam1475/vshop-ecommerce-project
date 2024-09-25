<script setup>
import { router } from "@inertiajs/vue3";
import { defineProps, onMounted, ref, watch } from "vue";
import { useRoute } from 'vue-router';

const props = defineProps({
  routeName: String
});

// const routes = useRoute();
// const search = ref(routes.query.search || '');
const params = new URLSearchParams(window.location.search);
const search = ref(params.get('search') || '');

function filter() {
  router.get(route(`${props.routeName}.list`), {
    ...(search.value != '' && { search: search.value }),
  },
  {
    preserveState: true,
    replace: true,
    preserveScroll: true
  }
  );
}
</script>

<template>
  <div class="w-full md:w-1/2">
    <form class="flex items-center" @submit.prevent="filter">
      <label for="simple-search" class="sr-only">Search</label>
      <div class="relative w-full">
        <div
          class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
        >
          <svg
            aria-hidden="true"
            class="w-5 h-5 text-gray-500 dark:text-gray-400"
            fill="currentColor"
            viewbox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
              clip-rule="evenodd"
            />
          </svg>
        </div>
        <input
          type="text"
          id="simple-search"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Search"
          v-model="search"
        />
      </div>
    </form>
  </div>
</template>