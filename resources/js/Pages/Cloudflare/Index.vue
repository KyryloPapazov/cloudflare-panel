<script>
import {useForm} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";

export default {
    components: {NavLink, AuthenticatedLayout},
    props: {
        accounts: Array,
    },
    setup() {


        const form = useForm({});


        function deleteAccount(id) {

            if (confirm('Are you sure you want to delete this account?')) {
                form.delete(route('cloudflare-accounts.destroy', id), {
                    onFinish: () => window.location.reload(),
                });
            }
        }

        return {
            deleteAccount,
        };
    }

};
</script>

<template>
    <AuthenticatedLayout>
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-medium text-gray-800">Cloudflare Accounts</h1>
                <NavLink :href="route('cloudflare-accounts.create')" class="flex btn btn-primary">
                    Add Account
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                    </svg>
                </NavLink>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg">
                    <thead>
                    <tr class="w-full text-center text-amber-500 bg-blue-200">
                        <th class="px-4 py-5 border">Name</th>
                        <th class="px-4 py-5 border">Email</th>
                        <th class="px-4 py-5 border">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="account in accounts" :key="account.id" class="border-t hover:bg-gray-100">
                        <td class="px-4 py-3 border">{{ account.name }}</td>
                        <td class="px-4 py-3 border">{{ account.email }}</td>
                        <td class="px-4 py-3 flex space-x-2">
                            <div class="flex mx-auto">
                                <a :href="route('cloudflare-accounts.edit', account.id)"
                                   class="flex mr-5 text-blue-500 pr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                    </svg>
                                    Edit
                                </a>
                                <a :href="route('cloudflare-accounts.show', account.id)"
                                   class="flex  mr-5 text-green-500 border-r border-l pr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                    View
                                </a>
                                <button @click="deleteAccount(account.id)" class="flex mx-auto text-right text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
