<template>
    <AuthenticatedLayout>
        <div>
            <!-- Отображение сообщений об успехе и ошибках -->
            <div v-if="success" class="alert alert-success">
                {{ success }}
            </div>
            <div v-if="error" class="alert alert-danger">
                {{ error }}
            </div>

            <h1>Manage Domains</h1>
            <NavLink :href="route('cloudflare-domains.create', 1)" class="flex btn btn-primary">
                Add domain
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                </svg>
            </NavLink>
            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead>
                <tr class="w-full text-center text-amber-500 bg-blue-200">
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
                    <td class="px-4 py-3">
                        <NavLink :href="route('domains.pagerules.index', domain.id)" class="btn btn-info">PageRules</NavLink>
                        <button @click="openEditModal(domain)" class="btn btn-info">Edit</button>
                        <button @click="deleteDomain(domain.id)" class="btn btn-danger">Delete</button>

                    </td>
                </tr>
                </tbody>
            </table>
            <!-- Модальное окно для редактирования SSL -->
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
