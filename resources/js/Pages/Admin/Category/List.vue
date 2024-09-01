<script setup>
import SingleCategory from '@/Pages/Admin/Category/Single.vue';
import Table from '@/Pages/Admin/Components/Table.vue';
import { defineProps } from 'vue';

const props = defineProps({
    categories: Object,
});


</script>

<template>
<Table :href="route('admin.category.add')" :links="categories.links">
    <template #tableHeader>
        <tr>
            <th scope="col" class="px-4 py-3">ID</th>
            <th scope="col" class="px-4 py-3">Name</th>
            <th scope="col" class="px-4 py-3">Slug</th>
            <th scope="col" class="px-4 py-3">Parent</th>
            <th scope="col" class="px-4 py-3">
                <span class="sr-only">Actions</span>
            </th>
        </tr>
    </template>
    
    <template #tableBody>
        <SingleCategory v-for="category in categories.data" :key="category.id" :category="category" />
    </template>


</Table>
</template>