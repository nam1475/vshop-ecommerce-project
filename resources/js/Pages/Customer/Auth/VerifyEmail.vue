<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Main from '@/Pages/Customer/Components/Layout/Main.vue';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <Main>
        <section class="bg-gray-50 dark:bg-gray-900">
            <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
                <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                        <div class="mb-4 text-sm text-gray-600">
                            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link
                            we just emailed to you? If you didn't receive the email, we will gladly send you another.
                        </div>

                        <div class="mb-4 font-medium text-sm text-green-600" v-if="verificationLinkSent">
                            A new verification link has been sent to the email address you provided during registration.
                        </div>

                        <form class="space-y-4 md:space-y-6" @submit.prevent="submit">
                            <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                Resend Verification Email
                            </button>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >Log Out
                            </Link>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </Main>
    
</template>
