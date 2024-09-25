<script setup>
import { defineProps } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { success, error, warning } from '@/alert.js';
import CheckboxChildren from "../Components/CheckboxChildren.vue";

defineProps({
    customer: Object
});

function deleteCustomer(id){
  warning()
    .then((result) => {
      if (result.isConfirmed) {
        try{
          router.delete(route("admin.customer.delete", id), {
            onSuccess: (page) => {
              success(page);
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
    <tr
        class="border-b dark:border-gray-700"
    >
        <td class="w-4 p-4">
          <CheckboxChildren :data="customer"/>
        </td>
        <th
          scope="row"
          class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
        >
          {{ customer.id }}
        </th>
        <td class="px-4 py-3">{{ customer.name }}</td>
        <td class="px-4 py-3">{{ customer.email }}</td>
        <td class="px-4 py-3">{{ customer.phone }}</td>
        <td class="px-4 py-3">{{ customer.created_at }}</td>
        <td class="px-4 py-3 flex items-center justify-end">
          <button
            :id="`${customer.id}-button`"
            :data-dropdown-toggle="`${customer.id}-dropdown`"
            class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
            type="button"
          >
            <svg
              class="w-5 h-5"
              aria-hidden="true"
              fill="currentColor"
              viewbox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"
              />
            </svg>
          </button>
          <div
            :id="`${customer.id}-dropdown`"
            class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
          >
            <ul
              class="py-1 text-sm text-gray-700 dark:text-gray-200"
              :id="`${customer.id}-button`"
            >
            </ul>
            <div class="py-1">
              <a
                @click="deleteCustomer(customer.id)"
                href="#"
                class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                >Delete</a
              >
            </div>
          </div>
        </td>
    </tr>
</template>