<script setup>
import FormAction from '@/Pages/Admin/Components/FormAction.vue';
import RecursiveSelected from '@/Pages/Admin/Components/RecursiveSelected.vue';
import { defineProps, ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { success, error } from "@/alert.js";
import SelectMultiple from '@/Pages/Admin/Components/SelectMultiple.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  role: Object,
  roleHasPermissions: Array,
  permissions: Array,
  title: String
});

const form = useForm({
  name: props.role.name,
  permissions: props.roleHasPermissions.map((item) => item.id),
});

function editRole() {
  form.patch(route("admin.role.update", props.role.id), {
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
<FormAction title="Edit role" :action="editRole">
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
        required=""
        v-model="form.name"
      />
    </div>

    <div class="mb-5">
      <label
        for="permissions"
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >Permissions</label
      >
        <SelectMultiple :data="permissions" @update:selectAll="form.permissions = $event" v-model:dataForm="form.permissions" />
    </div>
  </div>
</FormAction>
</template>