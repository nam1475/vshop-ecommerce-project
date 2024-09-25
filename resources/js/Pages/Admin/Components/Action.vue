<script setup>
import { computed, defineEmits, defineProps } from 'vue';
import { router } from '@inertiajs/vue3';
import { success, error, warning } from '@/alert.js';
import { useStore } from 'vuex';

const props = defineProps({
  routeName: String
});

const store = useStore();
const checkedRows = computed(() => store.getters['checkbox/checkedRows']);

function deleteChecked(){
  warning()
    .then((result) => {
      if (result.isConfirmed) {
        try{
          router.delete(route(`${props.routeName}.delete`, {ids: checkedRows.value}), 
          {
            onSuccess: (page) => {
              success(page);
              store.dispatch('checkbox/setCheckedAllRows', []);
            },
            onError: (page) => {
              error(page);
            },
          });
        } catch(err){
          console.log(err);
        }
      }
    });
}

</script>

<template>
  <button
    id="actionsDropdownButton"
    data-dropdown-toggle="actionsDropdown"
    class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
    type="button"
  >
    <svg class="w-[16px] h-[16px] text-gray-600 dark:text-white mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
      <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M20 6H10m0 0a2 2 0 1 0-4 0m4 0a2 2 0 1 1-4 0m0 0H4m16 6h-2m0 0a2 2 0 1 0-4 0m4 0a2 2 0 1 1-4 0m0 0H4m16 6H10m0 0a2 2 0 1 0-4 0m4 0a2 2 0 1 1-4 0m0 0H4"/>
    </svg>
    Actions
    <svg
      class="ml-1.5 mr-1.5 w-5 h-5"
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
    id="actionsDropdown"
    class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
  >
    <ul
      class="py-1 text-sm text-gray-700 dark:text-gray-200"
      aria-labelledby="actionsDropdownButton"
    >
      <li>
        <a
          href="#"
          class="block py-2 px-4 text-center hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
          >Mass Edit</a
        >
      </li>
    </ul>
    <div class="py-1">
      <button
        type="button"
        :disabled="!checkedRows.length" 
        @click="deleteChecked"
        class="block py-2 w-full text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
        :class="!checkedRows.length ? 'cursor-not-allowed' : ''"
      >
        Delete selection
      </button>
    </div>
  </div>
</template>