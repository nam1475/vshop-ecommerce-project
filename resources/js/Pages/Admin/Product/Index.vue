<script setup>
import { computed, defineProps, reactive, ref, watch } from "vue";
import Table from "@/Pages/Admin/Components/Table.vue";
import SingleProduct from "@/Pages/Admin/Product/Single.vue";
import { useStore } from "vuex";
import CheckboxAll from "@/Pages/Admin/Components/CheckboxAll.vue";

const props = defineProps({
  products: Object,
  brands: Array,
  categories: Array,
});

const data = reactive([
  {
    name: 'Brands',
    values: props.brands
  },
  {
    name: 'Categories',
    values: props.categories
  }
]); 

// watch(() => props.brands, (newVal) => {
//   data[0].values = newVal;
// });

// watch(() => props.categories, (newVal) => {
//   data[1].values = newVal;
// });  


</script>

<template>
  <!-- <Table :links="products.links" :filterOptions="brands" routeName="admin.product"> -->
  <Table :links="products.links" :filterOptions="data" routeName="admin.product">
    <template #tableHeader>
      <tr>
        <th scope="col" class="p-4">
          <CheckboxAll :data="products.data" />  
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