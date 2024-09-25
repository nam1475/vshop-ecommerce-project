<script setup>
import { useForm } from "@inertiajs/vue3";
import { success, error } from "@/alert.js";
import FormAction from '@/Pages/Admin/Components/FormAction.vue';
import { defineProps } from "vue";
import CheckboxAll from '@/Pages/Admin/Components/CheckboxAll.vue';
import CheckboxChildren from '@/Pages/Admin/Components/CheckboxChildren.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    tables: Array,
    actions: Array,
});

const form = useForm({
    permission: "",
    actions: [],
});

function addPermission() {
  form.post(route("admin.permission.store"), {
    onFinish: () => {
        form.reset();
    },
    // onSuccess: (page) => {
    //     success(page);
    // },
    // onError: (page) => {
    //     error(page);
    // },
  });
}

</script>

<template>
<FormAction title="Add New Permission" :action="addPermission">
  <div class="max-w-sm">
    <div class="mb-5">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Permission</label>
        <select id="name" v-model="form.permission" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value=""></option>
            <option v-for="table in tables" :key="table" :value="table">{{ table }}</option>
        </select>
        <InputError :message="form.errors.permission" />
    </div>

    <div class="mb-5">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Action</label>
        <div class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow">
            <div class="flex items-center mb-4">
                <CheckboxAll :data="actions" />
                <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Check all</label>
            </div>
            <div class="grid gap-4 grid-cols-4">
                <div class="flex items-center mb-4" v-for="action in actions" :key="action">
                    <input :id="action" type="checkbox" :value="action" v-model="form.actions" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" >
                    <!-- <CheckboxChildren :data="action"/> -->
                    <label :for="action" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ action }}</label>
                </div>
            </div>
        </div>
        <InputError :message="form.errors.actions" />
    </div>
  </div>
</FormAction>
</template>