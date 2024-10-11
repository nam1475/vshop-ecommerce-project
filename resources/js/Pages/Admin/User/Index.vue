<script setup>
import { defineProps } from 'vue';
import Table from "@/Pages/Admin/Components/Table.vue";
import SingleUser from "@/Pages/Admin/User/Single.vue";
import CheckboxAll from "@/Pages/Admin/Components/CheckboxAll.vue";

defineProps({
  users: Object
});

</script>

<template>
<Table routeName="admin.user" :links="users.links">
    <template #tableHeader>
      <tr>
        <th scope="col" class="p-4">
          <CheckboxAll :data="users.data" />  
        </th>
        <th scope="col" class="px-4 py-3">ID</th>
        <th scope="col" class="px-4 py-3">Name</th>
        <th scope="col" class="px-4 py-3">Email</th>
        <th scope="col" class="px-4 py-3">Roles</th>
        <th scope="col" class="px-4 py-3">
          <span class="sr-only">Actions</span>
        </th>
      </tr>
    </template>
    
    <template #tableBody>
      <SingleUser v-for="user in users.data" :key="user.id" :user="user" />
    </template>

</Table>
</template>