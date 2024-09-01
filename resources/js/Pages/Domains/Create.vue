
<script>
import {useForm} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";



export default {

    components: {NavLink, AuthenticatedLayout},
    props: {
        accounts: Array,
        success: String,
        error: String,

    },
    methods: {
        goBack() {
            window.history.back();
        }
    },

    setup() {
        const form = useForm({
            cloudflare_account_id: '',
            name: '',
        });

        function submit() {
            form.post(route('cloudflare-domains.store'));
        }

        return {form, submit};
    },

};
</script>

<template>
    <AuthenticatedLayout>

        <!-- Отображение сообщений об успехе и ошибках -->
        <div v-if="success" class="alert alert-success">
            {{ success }}
        </div>
        <div v-if="error" class="alert alert-danger">
            {{ error }}
        </div>
        <div class="max-w-2xl mx-auto mt-8 p-6 bg-white shadow-md rounded-lg">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Add Domain</h1>
            <form @submit.prevent="submit" class="space-y-4">
                <div class="form-group">
                    <label for="cloudflare_account_id" class="block text-sm font-medium text-gray-700 mb-1">Cloudflare Account</label>
                    <select v-model="form.cloudflare_account_id" class="form-control w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option v-for="account in accounts" :key="account.id" :value="account.id">
                            {{ account.name }} ({{ account.email }})
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Domain Name</label>
                    <input type="text" v-model="form.name" class="form-control w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="flex space-x-4 mt-6">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">Add Domain</button>
                    <button type="button" @click="goBack" class="bg-gray-100 text-gray-700 py-2 px-4 rounded hover:bg-gray-200">Cancel</button>

                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
