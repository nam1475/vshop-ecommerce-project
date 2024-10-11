<script setup>
import { defineProps } from 'vue';
import Table from "@/Pages/Admin/Components/Table.vue";
import SingleCustomer from "@/Pages/Admin/Customer/Single.vue";
import CheckboxAll from "@/Pages/Admin/Components/CheckboxAll.vue";

defineProps({
    customers: Object
});

</script>

<template>
<Table :links="customers.links" :canAdd="false" :canFilter="false">
    <template #tableHeader>
      <tr>
        <th scope="col" class="p-4">
          <CheckboxAll :data="customers.data" />  
        </th>
        <th scope="col" class="px-4 py-3">ID</th>
        <th scope="col" class="px-4 py-3">Name</th>
        <th scope="col" class="px-4 py-3">Email</th>
        <th scope="col" class="px-4 py-3">Phone</th>
        <th scope="col" class="px-4 py-3">Created at</th>
        <th scope="col" class="px-4 py-3">
          <span class="sr-only">Actions</span>
        </th>
      </tr>
    </template>
    
    <template #tableBody>
      <SingleCustomer v-for="customer in customers.data" :key="customer.id" :customer="customer" />
    </template>

</Table>
</template>