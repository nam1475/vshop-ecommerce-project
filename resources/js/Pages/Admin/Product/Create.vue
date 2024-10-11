<script setup>
import { defineProps, onMounted, ref } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { Plus } from "@element-plus/icons-vue";
import Main from "@/Pages/Admin/Components/Layout/Main.vue";
import { success, error } from "@/alert.js";
import FormAction from '@/Pages/Admin/Components/FormAction.vue';
import RecursiveSelected from '@/Pages/Admin/Components/RecursiveSelected.vue';
import InputError from '@/Components/InputError.vue';

defineProps({
  categories: Array,
  brands: Array,
});

const page = usePage();

const form = useForm({
  name: "",
  category_id: "",
  brand_id: "",
  quantity: 0,
  in_stock: 1,
  published: 1,
  price: 0,
  description: "",  
  product_images: [],
});

const dialogImageUrl = ref("");
const dialogVisible = ref(false);

function handlePictureCardPreview(file) {
  dialogImageUrl.value = file.url;
  dialogVisible.value = true;
}

function handleRemove(file) {
  form.product_images.splice(form.product_images.indexOf(file.raw), 1);
}

function handleFileUpload(file) {
  form.product_images.push(file.raw);
}

function addProduct() {
  form.post(route("admin.product.store"), {
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
<FormAction title="Add New Product" :action="addProduct">
  <div class="grid gap-4 mb-4 grid-cols-3">
    <div class="col-span-1">
      <label
        for="name"
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >Name</label
      >
      <input
        type="text"
        name="name"
        id="name"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
        v-model="form.name"
      />
      <InputError :message="form.errors.name" />
    </div>
    <div class="col-span-1">
      <label
        for="brand"
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >Brand</label
      >
      <select
        id="brand"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
        v-model="form.brand_id"
      >
        <option value=""></option>
        <option
          v-for="brand in brands"
          :value="brand.id"
          :key="brand.id"
          :selected="brand.id == form.brand_id"
        >
          {{ brand.name }}
        </option>
      </select>
      <InputError :message="form.errors.brand_id" />
    </div>
    <div class="col-span-1">
      <label
        for="category"
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >Category</label
      >
      <select
        id="category"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
        v-model="form.category_id"
      >
        <option value=""></option>
        <RecursiveSelected 
          v-for="category in categories"
          :id="category.id"
          :key="category.id"
          :category="category"
        />
      </select>
      <InputError :message="form.errors.category_id" />
    </div>
    <div class="col-span-1">
      <label
        for="price"
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >Price</label
      >
      <input
        type="number"
        id="price"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
        v-model="form.price"
      />
      <InputError :message="form.errors.price" />
    </div>
    <div class="col-span-1">
      <label
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >Published</label
      >
      <div>
        <input
          type="radio"
          id="published"
          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
          value="1"
          v-model="form.published"
        />
        <label
          for="published"
          class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
          >Yes</label
        >
      </div>
      <div>
        <input
          type="radio"
          id="no-published"
          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
          value="0"
          v-model="form.published"
        />
        <label
          for="no-published"
          class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
          >No</label
        >
      </div>
      <InputError :message="form.errors.published" />
    </div>

    <div class="col-span-1">
      <label
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >In stock</label
      >
      <div>
        <input
          type="radio"
          id="in-stock"
          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
          value="1"
          v-model="form.in_stock"
        />
        <label
          for="in-stock"
          class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
          >Yes</label
        >
      </div>
      <div>
        <input
          type="radio"
          id="out-stock"
          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
          value="0"
          v-model="form.in_stock"
        />
        <label
          for="out-stock"
          class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
          >No</label
        >
      </div>
      <InputError :message="form.errors.in_stock" />
    </div>
    <div class="col-span-1">
      <label
        for="quantity"
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >Quantity</label
      >
      <input
        type="number"
        name="quantity"
        id="quantity"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
        v-model="form.quantity"
      />
      <InputError :message="form.errors.quantity" />
    </div>

    <div class="col-span-2">
      <label
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >Description</label
      >
      <textarea
        id="description"
        rows="3"
        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        v-model="form.description"
      ></textarea>
      <InputError :message="form.errors.description" />
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
        :file-list="form.product_images"
        multiple
      >
        <el-icon><Plus /></el-icon>
      </el-upload>

      <el-dialog v-model="dialogVisible">
        <img w-full :src="dialogImageUrl" alt="Preview Image" />
      </el-dialog>
      <!-- <img v-for="(item, index) in form.product_images" :key="index" :src="item.image" alt="" class="w-20 h-30">   -->
      
      <InputError :message="form.errors.product_images" />
      
    </div>
  </div>
  </FormAction>
</template>