<script setup>
import { useForm } from "@inertiajs/vue3";
import { success, error } from "@/alert.js";
import FormAction from '@/Pages/Admin/Components/FormAction.vue';
import InputError from '@/Components/InputError.vue';
import SelectMultiple from '@/Pages/Admin/Components/SelectMultiple.vue';
import { defineProps } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
    roles: Array,
    userHasRoles: Array,
    title: String
});

const form = useForm({
  name: props.user.name,
  roles: props.userHasRoles.map((item) => item.id),
});

function updateUser() {
  form.patch(route("admin.user.update", props.user.id), {
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
<Head :title="title" />
<FormAction title="Add New User" :action="updateUser">
  <div class="grid gap-4 mb-4 grid-cols-2">
    <div class="col-span-1">
      <label
        for="name"
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >Name</label
      >
      <input
        type="text"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
        v-model="form.name"
      />
      <InputError :message="form.errors.name" />
    </div>
    <div class="col-span-1">
      <label
        for="roles"
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >Roles</label
      >
      <SelectMultiple :data="roles" @update:selectAll="form.roles = $event" v-model:dataForm="form.roles" />
    </div>
  </div>
</FormAction>
</template>