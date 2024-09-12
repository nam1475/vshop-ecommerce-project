<script setup>
import Table from '@/Pages/Admin/Components/Table.vue';
import { defineProps } from 'vue';
import SingleBrand from '@/Pages/Admin/Brand/Single.vue';

defineProps({
    brands: Object,
});


</script>

<template>
<Table routeName="admin.brand" :links="brands.links">
    <template #tableHeader>
        <tr>
            <th scope="col" class="px-4 py-3">ID</th>
            <th scope="col" class="px-4 py-3">Name</th>
            <th scope="col" class="px-4 py-3">Slug</th>
            <th scope="col" class="px-4 py-3">
                <span class="sr-only">Actions</span>
            </th>
        </tr>
    </template>
    
    <template #tableBody>
        <SingleBrand v-for="brand in brands.data" :key="brand.id" :brand="brand" />
    </template>
    
</Table>
</template>