<!-- resources/js/Pages/PageRules/Index.vue -->
<template>
    <Head title="Page Rules" />
    <AuthenticatedLayout>
        <div class="container mx-auto p-6 m-6 bg-white rounded-lg shadow-md">
            <div class="flex justify-between items-center ">
                <!-- Page Title -->
                <h1 class="text-2xl font-semibold text-gray-800 mb-6">Page Rules for {{ domain.name }}</h1>

                <!-- Add Page Rule Button -->
                <NavLink :href="route('domains.pagerules.create', domain.id)"
                         class="flex items-center px-4 py-2 mb-4 font-semibold rounded-md ">
                    Add Page Rule
                </NavLink>
            </div>

            <div v-if="pageRules.length === 0" class="text-center p-6">
                <p class="text-xl text-gray-700">No page rules available. Please create one.</p>
            </div>

            <div v-else class="overflow-x-auto">
                <!-- Page Rules Table -->
                <table class="w-11/12 mx-auto bg-white shadow-md rounded-lg">
                    <thead>
                    <tr class="bg-blue-200 text-gray-700">
                        <th class="px-4 py-5 border w-0.5">Position</th>
                        <th class="px-4 py-5 border">URL/Description</th>
                        <th class="px-4 py-5 w-1.5">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(rule, index) in pageRules" :key="rule.id" class="border-t hover:bg-gray-100">
                        <td class="px-4 py-3 border text-center">{{ index + 1 }}</td>
                        <td class="px-4 py-3 border">
                            <div>{{ rule.target_url }}</div>
                            <div v-if="rule.actions && rule.actions.length > 0" class="text-sm text-gray-500 mt-1">
                                <span v-for="(action, idx) in rule.actions" :key="idx">
                                    {{ formatAction(action) }}<span v-if="idx < rule.actions.length - 1">, </span>
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 flex border-b ">
                            <div class="px-4 py-3 flex  mx-auto">
                                <!-- Toggle Switch -->
                                <button @click="toggleStatus(rule, domain)"
                                        v-if="rule.status === 'active'"
                                        class="mx-2 text-left bg-green-500 text-white p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <span v-if="rule.status === 'active'">✓</span>
                                    <span v-else>✗</span>
                                </button>
                                <button @click="toggleStatus(rule, domain)"
                                        v-else
                                        class="mx-2 text-left bg-red-500 text-white p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <span v-if="rule.status === 'active'">✓</span>
                                    <span v-else>✗</span>
                                </button>
                                <!-- Edit Button -->
                                <button @click="editRule(domain.id, rule.id)"
                                        class="mx-5  p-2 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                    </svg>
                                </button>
                                <!-- Delete Button -->
                                <button @click="deleteRule(rule.id)"
                                        class="mx-5  text-right flex p-2 mx-auto text-right text-red-500 hover:bg-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                    </svg>
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


<script>
import {useForm} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";
import { Head } from '@inertiajs/vue3';

export default {
    components: {AuthenticatedLayout, NavLink, Head},
    props: {
        domain: Object,
        pageRules: Array,
    },
    setup() {
        const form = useForm({});

        function formatAction(action) {
            if (!action || !action.id) {
                return '';  // Return an empty string if action or id is undefined
            }

            // Handle cases where action.value might be undefined or not a string
            const formattedValue = action.value && typeof action.value === 'string'
                ? action.value.charAt(0).toUpperCase() + action.value.slice(1)
                : 'N/A';  // Default value if action.value is undefined

            return `${action.id.toUpperCase()}: ${formattedValue}`;
        }

        function toggleStatus(rule, domain) {
            // Здесь логика для переключения статуса
            rule.status = rule.status === 'active' ? 'disabled' : 'active';
            console.log(domain.id);
            console.log(rule.id);

            const payload = {
                status: rule.status,
                target_url: rule.target_url, // Ensure this is the correct target URL
                 actions: Array.isArray(rule.actions) && rule.actions.length > 0 ? rule.actions : null  // Ensure this includes the correct actions
            };


            // Make an Axios PUT request to update the page rule
            axios.put(route('domains.pagerules.update', {domainId: domain.id, ruleId: rule.id}), payload);

        }

        function editRule(domainId, ruleId) {
            // Логика редактирования правила
            window.location.href = route('domains.pagerules.edit', {domainId: domainId, ruleId: ruleId});
        }

        function deleteRule(id) {
            if (confirm('Are you sure you want to delete this page rule?')) {
                form.delete(route('pagerules.destroy', id));
            }
        }

        return {
            deleteRule,
            editRule,
            toggleStatus,
            formatAction,
        };
    },
};
</script>
