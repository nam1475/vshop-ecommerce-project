<script setup>
import FormAction from '@/Pages/Admin/Components/FormAction.vue';
import RecursiveSelected from '@/Pages/Admin/Components/RecursiveSelected.vue';
import { defineProps } from "vue";
import { useForm } from "@inertiajs/vue3";
import { success, error } from "@/alert.js";

const props = defineProps({
    category: Object,
    categories: Array
});

const form = useForm({
    id: props.category.id,
    name: props.category.name,
    parent_id: props.category.parent_id,
});

function editCategory() {
  form.put(route("admin.category.update", props.category.id), {
    onSuccess: (page) => {
        success(page);
    },
    onError: (page) => {
        error(page);
    },
  });
}

</script>

<template>
<FormAction title="Edit Category" :action="editCategory">
  <div class="grid gap-4 mb-4 grid-cols-3">
    <div class="col-span-1">
      <label
        for="name"
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >Name</label
      >
      <input
        type="text"
        id="name"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
        required=""
        v-model="form.name"
      />
    </div>
    <div class="col-span-1">
      <label
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >Category</label
      >
      <select
        id="category"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
        v-model="form.parent_id"
      >
        <option value=""></option>
        <RecursiveSelected 
          v-for="ca in categories"
          :key="ca.id"
          :id="ca.id"
          :category="ca"
          :selected="form.id == ca.id"
        />
      </select>
    </div>
  </div>
</FormAction>
</template>