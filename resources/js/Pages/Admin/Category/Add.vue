<script setup>
import Main from '@/Pages/Admin/Components/Layout/Main.vue';
import { useForm } from "@inertiajs/vue3";
import { defineProps, ref } from "vue";
import { success, error } from "@/alert.js";
import RecursiveSelected from '@/Pages/Admin/Components/RecursiveSelected.vue';
import FormAction from '@/Pages/Admin/Components/FormAction.vue';
import { Plus } from "@element-plus/icons-vue";

defineProps({
  categories: Array
});

const form = useForm({
  name: "",
  parent_id: "",
  url: null,
});

const dialogImageUrl = ref("");
const dialogVisible = ref(false);

function handlePictureCardPreview(file) {
  dialogImageUrl.value = file.url;
  dialogVisible.value = true;
}

function handleRemove(file) {
  form.url = null;
}

function handleFileUpload(file) {
  form.url = file.raw;
}

function addCategory() {
  form.post(route("admin.category.store"), {
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
<FormAction title="Add New Category" :action="addCategory">
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
          v-for="category in categories"
          :id="category.id"
          :key="category.id"
          :category="category"
        />
      </select>
    </div>

    <div class="col-span-2">
      <label
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >Images</label
      >
      <el-upload
        list-type="picture-card"
        :on-preview="handlePictureCardPreview"
        :on-remove="handleRemove"
        :auto-upload="false"
        :on-change="handleFileUpload"
        :limit="1"
      >
        <el-icon><Plus /></el-icon>
      </el-upload>

      <el-dialog v-model="dialogVisible">
        <img w-full :src="dialogImageUrl" alt="Preview Image" />
      </el-dialog>
    </div>
  </div>
</FormAction>
</template>