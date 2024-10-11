<script setup>
import { useForm } from "@inertiajs/vue3";
import { success, error } from "@/alert.js";
import FormAction from '@/Pages/Admin/Components/FormAction.vue';
import InputError from '@/Components/InputError.vue';

const form = useForm({
    name: "",
});

function addBrand() {
  form.post(route("admin.brand.store"), {
    onFinish: () => {
        form.reset();
    },
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
<FormAction title="Add New Brand" :action="addBrand">
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
        v-model="form.name"
      />
      <InputError :message="form.errors.name" />
    </div>
  </div>
</FormAction>
</template>