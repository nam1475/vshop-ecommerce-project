<script setup>
import { useForm } from "@inertiajs/vue3";
import { success, error } from "@/alert.js";
import FormAction from '@/Pages/Admin/Components/FormAction.vue';
import { ref, defineProps, watch } from "vue";
import SelectMultiple from '@/Pages/Admin/Components/SelectMultiple.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
  permissions: Array,
});

const form = useForm({
  name: "",
  permissions: [],
});

function addRole() {
  form.post(route("admin.role.store"), {
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
<FormAction title="Add New Role" :action="addRole">
  <div class="max-w-sm">
    <div class="mb-5">
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

    <div class="mb-5">
      <label
        for="permissions"
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >Permissions</label
      >
      <SelectMultiple :data="permissions" @update:selectAll="form.permissions = $event" v-model:dataForm="form.permissions" />
      
      <InputError :message="form.errors.permissions" />
    </div>
  </div>
</FormAction>
</template>