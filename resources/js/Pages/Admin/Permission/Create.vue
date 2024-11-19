<script setup>
import { useForm } from "@inertiajs/vue3";
import FormAction from '@/Pages/Admin/Components/FormAction.vue';
import { computed, defineProps, ref } from "vue";
import CheckboxAll from '@/Pages/Admin/Components/CheckboxAll.vue';
import CheckboxChildren from '@/Pages/Admin/Components/CheckboxChildren.vue';
import InputError from '@/Components/InputError.vue';
import { useStore } from "vuex";
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    tables: Array,
    actions: Array,
    title: String
});

const actionNames = ref([...props.actions]);
const store = useStore();

const form = useForm({
    permission: "",
    // actions: [],
    actions: computed(() => store.getters["checkbox/checkedRows"]),
});

function addPermission() {
  form.post(route("admin.permission.store"), {
    onFinish: () => {
        form.reset();
    },
  });
}

function updateActions(table) {
    switch (table) {
        case "customer":
            actionNames.value = ['index', 'edit', 'delete'];
            break;
        case "order":
            actionNames.value = ["index", "edit", "delete"];
            break;
        case 'dashboard':
            actionNames.value = ['index'];
            break;
        default:
            actionNames.value = ["index", "create", "edit", "delete"];
            break;
    }
}

</script>

<template>
<Head :title="title" />
<FormAction title="Add New Permission" :action="addPermission">
  <div class="max-w-sm">
    <div class="mb-5">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Permission</label>
        <select id="name" v-model="form.permission" @change="updateActions(form.permission)" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value=""></option>
            <option v-for="table in tables" :key="table" :value="table">{{ table }}</option>
        </select>
        <InputError :message="form.errors.permission" />
    </div>

    <div class="mb-5">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Action</label>
        <div class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow">
            <div class="flex items-center mb-4">
                <CheckboxAll :data="actionNames" />
                <label for="checkbox-all" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Check all</label>
            </div>
            <div class="grid gap-4 grid-cols-4">
                <div class="flex items-center mb-4" v-for="action in actionNames" :key="action">
                    <CheckboxChildren :data="action" />
                    <label :for="action" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ action }}</label>
                </div>
            </div>
        </div>
        <InputError :message="form.errors.actions" />
    </div>
  </div>
</FormAction>
</template>