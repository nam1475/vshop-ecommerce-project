<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { defineProps, onMounted, ref, watch } from "vue";
import { useRoute } from 'vue-router';

const props = defineProps({
  filterOptions: Array,
  routeName: String
});

// const routes = useRoute();

// const currentRoute = usePage().url;

const params = new URLSearchParams(window.location.search);
const queryFilter = ref(params.getAll('filter[]'));

// const queryFilter = ref(routes.query.filter ? routes.query.filter : []);
// console.log(routes.query.filter);

watch(queryFilter, () => {
  filter();
});

function filter() {
  router.get(route(`${props.routeName}.index`), {
    filter: queryFilter.value
  },
  {
    preserveState: true,
    replace: true,
    preserveScroll: true
  }
  );
}

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

</script>

<template>
  <button
    id="filterDropdownButton"
    data-dropdown-toggle="filterDropdown"
    class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
    type="button"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      aria-hidden="true"
      class="h-4 w-4 mr-2 text-gray-400"
      viewbox="0 0 20 20"
      fill="currentColor"
    >
      <path
        fill-rule="evenodd"
        d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
        clip-rule="evenodd"
      />
    </svg>
    Filter
    <svg
      class="-mr-1 ml-1.5 w-5 h-5"
      fill="currentColor"
      viewbox="0 0 20 20"
      xmlns="http://www.w3.org/2000/svg"
      aria-hidden="true"
    >
      <path
        clip-rule="evenodd"
        fill-rule="evenodd"
        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
      />
    </svg>
  </button>
  
  <div
    id="filterDropdown"
    class="flex justify-between z-10 hidden p-5 bg-white rounded-lg shadow"
  >
    <div class="mx-2" v-for="option in filterOptions" :key="option.name">
      <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">
        {{ option.name }}
      </h6>
      <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton" v-if="option.name == 'Status'">
        <li class="flex items-center" v-for="item in option.values" :key="item.status">
          <input
            :id="item.status"
            type="checkbox"
            :value="item.status"
            v-model="queryFilter"
            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
          />
          <label
            :for="item.status"
            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
          >
            {{ capitalizeFirstLetter(item.status) }}
            <span class="text-gray-400">({{ item.count }})</span> 
          </label>
        </li>
      </ul>

      <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton" v-else>
        <li class="flex items-center" v-for="item in option.values" :key="item.id">
          <input
            :id="item.id"
            type="checkbox"
            :value="item.name"
            v-model="queryFilter"
            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
          />
          <label
            :for="item.id"
            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
          >
            {{ item.name }} 
            <span class="text-gray-400">({{ item.products_count }})</span> 
          </label>
        </li>
      </ul>
    </div>

  </div>
</template>