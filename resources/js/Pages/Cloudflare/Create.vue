<script >
import {Head, useForm} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";
import Notification from "@/Components/Notification.vue";

import {useToast} from "vue-toastification";
import {onMounted, watch} from "vue";

export default {
    components: {Head, NavLink, AuthenticatedLayout, Notification},

    props: {
        success: String,
        error: String,
        // flash: Object, // Получение флеш-сообщений из Inertia
    },

    setup(props) {
        const toast = useToast();
        onMounted(() => {
            if (props.error) {
                toast.error(props.error);
            }
            if (props.success) {
                toast.success(props.success);
            }
        });




        const form = useForm({
            name: '',
            email: '',
            api_key: '',
        });

        function submit() {
            form.post('/cloudflare-accounts',{
                onFinish: () => {
                    if (props.error) {
                        toast.error(props.error);
                    }
                    if (props.success) {
                        toast.success(props.success);
                    }
                }
            });
        }

        return {form, submit};
    },
};
</script>

<style scoped>
.form-input {
    @apply border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent;
}

.btn {
    @apply inline-block px-4 py-2 bg-red-700 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75;
}
</style>

<template>
    <Head title="Create Account" />
    <AuthenticatedLayout>
<!--        <div @click="showToast">Показати сповіщення</div>-->
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white shadow-md rounded-lg p-6">

<!--                <Notification v-if="flash && flash.success" :message="flash.success" type="success" />-->
<!--                <Notification v-if="flash && flash.error" :message="flash.error" type="error" />-->

                <h1 class="text-2xl font-medium text-gray-800 mb-4">Add Cloudflare Account</h1>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
                        <input v-model="form.name" type="text" id="name" class="form-input mt-1 block w-full" required/>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                        <input v-model="form.email" type="email" id="email" class="form-input mt-1 block w-full"
                               required/>
                    </div>
                    <div class="mb-4">
                        <label for="api_key" class="block text-gray-700 font-medium mb-2">API Key</label>
                        <input v-model="form.api_key" type="text" id="api_key" class="form-input mt-1 block w-full"
                               required/>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Account</button>
                    <NavLink class="ml-3 h-12" :href="route('cloudflare-accounts.index')">Cancel</NavLink>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

