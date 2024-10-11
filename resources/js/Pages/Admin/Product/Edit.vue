<script setup>
import { defineProps, ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import Main from "@/Pages/Admin/Components/Layout/Main.vue";
import { success, error, warning } from "@/alert.js";
import FormAction from '@/Pages/Admin/Components/FormAction.vue';
import RecursiveSelected from '@/Pages/Admin/Components/RecursiveSelected.vue';
import { Delete, Download, Plus, ZoomIn } from '@element-plus/icons-vue'

const props = defineProps({
  product: Object,
  categories: Array,
  brands: Array,
  images: Array,
});

const form = useForm({
  id: props.product.id,
  name: props.product.name,
  slug: props.product.slug,
  category_id: props.product.category_id,
  brand_id: props.product.brand_id,
  quantity: props.product.quantity,
  in_stock: props.product.in_stock,
  published: props.product.published,
  description: props.product.description,
  price: props.product.price,
  product_images: props.images,
});

const dialogImageUrl = ref("");
const dialogVisible = ref(false);
const uploadImages = ref([]);

function handlePictureCardPreview(file) {
  dialogImageUrl.value = file.url;
  dialogVisible.value = true;
}

function handleRemove(file) {
  if (file.id) {
    form.delete(route("admin.product.delete.image", { productId: form.id, imageId: file.id }), {
      onSuccess: (page) => {  
        success(page);
      },
      onError: (page) => {
        error(page);
      },
    });
  }
  else{
    uploadImages.value.splice(uploadImages.value.indexOf(file.raw), 1);
  }
}

function handleFileUpload(file) {
  uploadImages.value.push(file.raw);
}

function updateProduct() {
  const formData = new FormData();
  formData.append("name", form.name);
  formData.append("price", form.price);
  formData.append("quantity", form.quantity);
  formData.append("description", form.description);
  formData.append("category_id", form.category_id);
  formData.append("brand_id", form.brand_id);
  formData.append("in_stock", form.in_stock);
  formData.append("published", form.published);
  formData.append("_method", "PUT");
  // Append product images to the FormData
  for (const image of uploadImages.value) {
    formData.append("product_images[]", image);
  }

  try {
    router.post(route("admin.product.update", form.id), formData, {
      onSuccess: (page) => {
        success(page);
      },
      onError: (page) => {
        error(page);
      },
    });
  } catch (err) {
    console.log(err);
  }
}
</script>

<template>
<FormAction title="Edit Product" :action="updateProduct">
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
          v-for="ca in categories"
          :key="ca.id"
          :id="ca.id"
          :category="ca"
          :selected="form.category_id == ca.id"
        />
      </select>
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
    </div>
    <div class="col-span-1">
      <label
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >Published</label
      >
      <div>
        <input
          type="radio"
          name="published"
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
          name="published"
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
    </div>

    <div class="col-span-1">
      <label
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >In stock</label
      >
      <div>
        <input
          type="radio"
          name="in_stock"
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
          name="in_stock"
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
        :on-change="handleFileUpload"
        :auto-upload="false"
        :file-list="form.product_images"
        multiple
      >
        <el-icon><Plus /></el-icon>
      </el-upload>

      <el-dialog v-model="dialogVisible">
        <img w-full :src="dialogImageUrl" alt="Preview Image" />
      </el-dialog>  

      <!-- <img v-for="(item, index) in form.product_images" :key="index" :src="item" alt="" class="w-20 h-30">   -->

      <!-- <div v-if="form.product_images.length" class="flex flex-wrap gap-4 mt-4">
        <div v-for="image in form.product_images" :key="image.id" class="image-item">
          <img :src="image.url" alt="Product Image" class="w-[150px] h-[150px] rounded-lg" />
          <el-button type="danger" class="mt-2" @click="deleteImage(image.id)">Delete</el-button>
        </div>
      </div> -->

    </div>
  </div>
</FormAction>
</template>