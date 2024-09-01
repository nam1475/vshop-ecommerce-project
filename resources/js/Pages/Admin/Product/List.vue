<script setup>
import { defineProps } from "vue";
import Table from "@/Pages/Admin/Components/Table.vue";
import SingleProduct from "@/Pages/Admin/Product/Single.vue";

defineProps({
  products: Object,
});

</script>

<template>
  <Table :href="route('admin.product.add')" :links="products.links">
    <template #tableHeader>
      <tr>
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
      <SingleProduct v-for="product in products.data" :key="product.id" :product="product" />
    </template>

  </Table>
</template>