<script setup>
import FormAction from '@/Pages/Admin/Components/FormAction.vue';
import RecursiveSelected from '@/Pages/Admin/Components/RecursiveSelected.vue';
import { defineProps, ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import { success, error } from "@/alert.js";
import { Plus } from "@element-plus/icons-vue";

const props = defineProps({
  category: Object,
  categories: Array
});

const form = useForm({
  name: props.category.name,
  parent_id: props.category.parent_id != null ? props.category.parent_id : "",
  url: props.category.url,
});

const uploadImage = ref([]);
if(props.category.url != null){
  uploadImage.value.push({'url': props.category.url});
}

function updateCategory() {
  const formData = new FormData();
  formData.append("name", form.name);
  formData.append("parent_id", form.parent_id);
  formData.append("_method", "PUT");
  // uploadImage.value.forEach((file) => {
  //   formData.append("url[]", file);
  // });
  formData.append("image", uploadImage.value[0]);

  router.post(route("admin.category.update", props.category.id), formData, {
    onSuccess: (page) => {
        success(page);
    },
    onError: (page) => {
        error(page);
    },
  });
}

const dialogImageUrl = ref("");
const dialogVisible = ref(false);

function handlePictureCardPreview(file) {
  dialogImageUrl.value = file.url;
  dialogVisible.value = true;
}

function handleRemove(file) {
  if (!file.raw) {
    form.delete(route("admin.category.delete.image", props.category.id), {
      onSuccess: (page) => {
        success(page);
        uploadImage.value = [];
      },
      onError: (page) => {
        error(page);
      },
    });
  }
  else{
    uploadImage.value.splice(uploadImage.value.indexOf(file.raw), 1);
  }
}

function handleFileUpload(file) {
  uploadImage.value.push(file.raw);
}

</script>

<template>
<FormAction title="Edit Category" :action="updateCategory">
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

    <div class="col-span-3">
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
        :file-list="uploadImage ? uploadImage : []"
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