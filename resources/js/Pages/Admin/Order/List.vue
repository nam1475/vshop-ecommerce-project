<script setup>
import { defineProps, reactive } from 'vue';
import Table from "@/Pages/Admin/Components/Table.vue";
import SingleOrder from "@/Pages/Admin/Order/Single.vue";
import CheckboxAll from "@/Pages/Admin/Components/CheckboxAll.vue";

const props = defineProps({
  orders: Object,
  statuses: Array
});

const data = reactive([
  {
    name: 'Status',
    values: props.statuses
  }
]);
</script>

<template>
<Table :links="orders.meta.links" :canAdd="false" routeName="admin.order" :filterOptions="data">
    <template #tableHeader>
      <tr>
        <th scope="col" class="p-4">
          <CheckboxAll :data="orders.data" />  
        </th>
        <th scope="col" class="px-4 py-3">ID</th>
        <th scope="col" class="px-4 py-3">Total</th>
        <th scope="col" class="px-4 py-3">Status</th>
        <th scope="col" class="px-4 py-3">Created at</th>
        <th scope="col" class="px-4 py-3">
          <span class="sr-only">Actions</span>
        </th>
      </tr>
    </template>
    
    <template #tableBody>
      <SingleOrder v-for="order in orders.data" :key="order.id" :order="order" />
    </template>

</Table>
</template>