<script setup>
import { defineProps } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import { success, error, warning } from '@/alert.js';

defineProps({
    product: Object,
});

function deleteProduct(id){
  warning()
    .then((result) => {
      if (result.isConfirmed) {
        try{
          router.delete(route("admin.product.delete", id), {
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
        <th
          scope="row"
          class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
        >
          {{ product.id }}
        </th>
        <td class="px-4 py-3">{{ product.name }}</td>
        <td class="px-4 py-3">{{ product.slug }}</td>
        <td class="px-4 py-3">
          {{ product.category == null ? "-" : product.category.name }}
        </td>
        <td class="px-4 py-3">
          {{ product.brand == null ? "-" : product.brand.name }}
        </td>
        <td class="px-4 py-3">{{ product.price }}</td>
        <td class="px-4 py-3">{{ product.quantity }}</td>
        <td class="px-4 py-3" v-if="product.in_stock">
          <span
            class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300"
          >
            In stock
          </span>
        </td>
        <td class="px-4 py-3" v-else>
          <span
            class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300"
          >
            Out of stock
          </span>
        </td>
        <td class="px-4 py-3" v-if="product.published">
          <span
            class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300"
          >
            Published
          </span>
        </td>
        <td class="px-4 py-3" v-else>
          <span
            class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300"
          >
            Unpublished
          </span>
        </td>
        <td class="px-4 py-3">{{ product.created_at }}</td>
        <td class="px-4 py-3 flex items-center justify-end">
          <button
            :id="`${product.id}-button`"
            :data-dropdown-toggle="`${product.id}-dropdown`"
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
            :id="`${product.id}-dropdown`"
            class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
          >
            <ul
              class="py-1 text-sm text-gray-700 dark:text-gray-200"
              :id="`${product.id}-button`"
            >
              <li>
                <!-- <a
                  @click="editProduct(product)"
                  class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                  data-modal-target="crud-modal"
                  data-modal-toggle="crud-modal"
                  >Edit
                </a> -->
                <Link
                  :href="route('admin.product.edit', product.id)"
                  class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                  >Edit
                </Link>
              </li>
            </ul>
            <div class="py-1">
              <a
                @click="deleteProduct(product.id)"
                href="#"
                class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                >Delete</a
              >
            </div>
          </div>
        </td>
    </tr>
</template>