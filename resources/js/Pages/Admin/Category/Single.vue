<script setup>
import { defineProps, ref, computed } from "vue";
import SingleCategory from '@/Pages/Admin/Category/Single.vue';
import { Link, router } from "@inertiajs/vue3";
import { success, error, warning } from "@/alert";
import { useStore } from 'vuex';
import CheckboxChildren from "../Components/CheckboxChildren.vue";

const props = defineProps({
    category: Object,
    char: {
        type: String,
        default: ""
    }
});

function deleteCategory(id){
    warning()
    .then((result) => {
        if (result.isConfirmed) {
            try{
                router.delete(route("admin.category.destroy", id), {
                    onSuccess: (page) => {
                        success(page);
                    },
                    onError: (page) => {
                        error(page);
                    },
                });
            } catch(err){
                console.log(err);
            }
        }
    });
}

</script>

<template>
    <tr class="border-b dark:border-gray-700">    
        <td class="w-4 p-4">
            <CheckboxChildren :data="category.id"/>
        </td>
        <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ category.id }}</th>
        <td class="px-4 py-3">{{ char + category.name }}</td>
        <td class="px-4 py-3">{{ category.slug }}</td>
        <td class="px-4 py-3">{{ category.parent ? category.parent.name : ''  }}</td>
        <td class="px-4 py-3 flex items-center justify-end">
            <button :id="`${category.id}-button`" :data-dropdown-toggle="`${category.id}-dropdown`" class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
            </button>
            <div :id="`${category.id}-dropdown`" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" :aria-labelledby="`${category.id}-button`">
                    <li>
                        <Link :href="route('admin.category.edit', category.id)" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</Link>
                    </li>
                </ul>
                <div class="py-1">
                    <a @click="deleteCategory(category.id)" href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                </div>
            </div>
        </td>
    </tr>
    
    <template v-if="category.children_recursive.length">
        <SingleCategory v-for="child in category.children_recursive" :key="child.id" :category="child" :char="char + '--'" />
    </template>
        
</template>