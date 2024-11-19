<script setup>
import DeleteUserForm from '../Profile/Partials/DeleteUserForm.vue';
import UpdatePasswordForm from '../Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '../Profile/Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import Main from '@/Pages/Admin/Components/Layout/Main.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    title: String
});
</script>

<template>
    <Head :title="title" />
    
    <Main>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
        </template>
            
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :mustVerifyEmail="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </Main>
</template>
