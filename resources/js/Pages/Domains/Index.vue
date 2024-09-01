<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-6 m-6 bg-white rounded-lg shadow-md">

            <!-- Header with title and button -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-medium text-gray-800">Manage Domains</h1>
                <NavLink :href="route('cloudflare-domains.create', 1)" class="flex items-center px-4 py-2 font-semibold rounded-md">
                    Add domain
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-5 h-5 ml-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                    </svg>
                </NavLink>
            </div>

            <!-- Table for managing domains -->
            <div class="overflow-x-auto">
                <table class="w-11/12 mx-auto bg-white shadow-md rounded-lg">
                    <thead>
                    <tr class="text-center text-amber-500 bg-blue-200">
                        <th class="px-4 py-5 border">Domain</th>
                        <th class="px-4 py-5 border">Account</th>
                        <th class="px-4 py-5 border">Status</th>
                        <th class="px-4 py-5 border">SSL Mode</th>
                        <th class="px-4 py-5 border">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="domain in domains" :key="domain.id" class="border-t hover:bg-gray-100">
                        <td class="px-4 py-3 border">{{ domain.name }}</td>
                        <td class="px-4 py-3 border">{{ domain.cloudflare_account.name }} ({{ domain.cloudflare_account.email }})</td>
                        <td class="px-4 py-3 border">{{ domain.status }}</td>
                        <td class="px-4 py-3 border">{{ domain.ssl_mode }}</td>
                        <td class="px-4 py-3 flex justify-center space-x-4">
                            <NavLink :href="route('domains.pagerules.index', domain.id)" class="text-blue-500 hover:underline">PageRules</NavLink>
                            <button @click="openEditModal(domain)" class="text-yellow-500 hover:underline">Edit</button>
                            <button @click="deleteDomain(domain.id)" class="flex text-red-500 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                                Delete</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal for editing domain details -->
            <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                    <EditDomain :domain="selectedDomain" @close="closeEditModal" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";
import EditDomain from "@/Pages/Domains/Edit.vue";



export default {

    components: {AuthenticatedLayout, NavLink, EditDomain },
    props: {
        domains: Array,
        success: String,
        error: String,
    },
    data() {
        return {
            isModalOpen: false,
            selectedDomain: null,
        };
    },
    setup() {
        const form = useForm({});

        function deleteDomain(id) {
            if (confirm('Are you sure you want to delete this domain?')) {
                form.delete(route('cloudflare-domains.destroy', id), {
                    onFinish: () => window.location.reload(),
                });
            }
        }

        return {
            deleteDomain,
        };
    },
    methods: {
        openEditModal(domain) {
            this.selectedDomain = domain;  // Устанавливаем выбранный домен
            this.isModalOpen = true;  // Открываем модальное окно
        },
        closeEditModal() {
            this.isModalOpen = false;  // Закрываем модальное окно
            this.selectedDomain = null;  // Очищаем выбранный домен
        },

    },
};
</script>

<style>
/* Стили для модального окна */
.fixed {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 50;
}
</style>
