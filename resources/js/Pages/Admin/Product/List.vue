<script setup>
import { computed, defineProps, ref } from "vue";
import Table from "@/Pages/Admin/Components/Table.vue";
import SingleProduct from "@/Pages/Admin/Product/Single.vue";
import { useStore } from "vuex";

const props = defineProps({
  products: Object,
  brands: Array,
});

const store = useStore();

const isCheckedAll = ref(false); 
// const checkedRows = computed(() => store.getters['checkbox/checkedRows']);
// const checkedAllRows = ref([]);

function toggleCheckAll() {
  isCheckedAll.value = !isCheckedAll.value;
  if (isCheckedAll.value) {
    // checkedAllRows.value = props.products.data.map(product => product.id);
    // setCheckedAllRows(checkedAllRows.value);
    setCheckedAllRows(props.products.data);
  }
  else{
    // checkedAllRows.value = [];
    // setCheckedAllRows(checkedAllRows.value);
    setCheckedAllRows([]);
  } 
}

function setCheckedAllRows(values) {
  store.dispatch('checkbox/setCheckedAllRows', values);
}

</script>

<template>
  <!-- <Table :href="route('admin.product.add')" :links="products.links" :filterOptions="brands" routeName="admin.product"> -->
  <Table :links="products.links" :filterOptions="brands" routeName="admin.product">
    <template #tableHeader>
      <tr>
        <th scope="col" class="p-4">
          <div class="flex items-center">
              <input id="checkbox-all" v-model="isCheckedAll" @click="toggleCheckAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
              <label for="checkbox-all" class="sr-only">checkbox</label>
          </div>  
        </th>
        <th scope="col" class="px-4 py-3">ID</th>  
        <th scope="col" class="px-4 py-3">Name</th> 
        <th scope="col" class="px-4 py-3">Slug</th> 
        <th scope="col" class="px-4 py-3">Category</th>
        <th scope="col" class="px-4 py-3">Brand</th>
        <th scope="col" class="px-4 py-3">Price</th>
        <th scope="col" class="px-4 py-3">Quantity</th>
        <th scope="col" class="px-4 py-3">Stock</th>
        <th scope="col" class="px-4 py-3">Published</th>
        <th scope="col" class="px-4 py-3">Created at</th>
        <th scope="col" class="px-4 py-3">
          <span class="sr-only">Actions</span>
        </th>
      </tr>
    </template>

    <template #tableBody>
      <!-- <SingleProduct 
        v-for="product in products.data" 
        :key="product.id" 
        :product="product" 
        @setCheckedAllRows="setCheckedAllRows" 
        :checkedAllRows="checkedAllRows" 
      /> -->

      <SingleProduct 
        v-for="product in products.data" 
        :key="product.id" 
        :product="product" 
      />
    </template>

  </Table>
</template>