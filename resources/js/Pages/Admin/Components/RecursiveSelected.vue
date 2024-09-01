<script setup>
import { defineProps, ref } from "vue";
import RecursiveSelected from '@/Pages/Admin/Components/RecursiveSelected.vue';

defineProps({
    id: Number,
    category: Object,
    char: {
        type: String,
        default: ""
    },
    selected: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <option :value="id" :selected="selected">
        {{ char + category.name }}
    </option>
    <RecursiveSelected 
        v-for="child in category.children_recursive" 
        :key="child.id" 
        :category="child" 
        :id="child.id" 
        :char="char + '--'"
    />

</template>