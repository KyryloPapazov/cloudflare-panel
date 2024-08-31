<!-- resources/js/Pages/PageRules/Index.vue -->
<template>
    <AuthenticatedLayout>
        <div>
            <h1>Page Rules for {{ domain.name }}</h1>
            <NavLink :href="route('domains.pagerules.create', domain.id)" class="btn btn-primary">Add Page Rule</NavLink>

            <table class="min-w-full bg-white shadow-md rounded-lg mt-4">
                <thead>
                <tr>
                    <th class="px-4 py-5 border">Position</th>
                    <th class="px-4 py-5 border">URL/Description</th>
                    <th class="px-4 py-5 border">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(rule, index) in pageRules" :key="rule.id" class="border-t hover:bg-gray-100">
                    <td class="px-4 py-3 border">{{ index + 1 }}</td>
                    <td class="px-4 py-3 border">
                        <div>{{ rule.target_url }}</div>
                        <div v-if="rule.actions && rule.actions.length > 0">
                <span v-for="(action, idx) in rule.actions" :key="idx">
                  {{ formatAction(action) }}<span v-if="idx < rule.actions.length - 1">, </span>
                </span>
                        </div>
                    </td>
                    <td class="px-4 py-3 border flex items-center space-x-2">
                        <!-- Toggle Switch -->
                        <button @click="toggleStatus(rule, domain)" class="bg-green-500 text-white p-2 rounded-full">
                            <span v-if="rule.status === 'active'">✓</span>
                            <span v-else>✗</span>
                        </button>
                        <!-- Edit Button -->
                        <button @click="editRule(rule.id)" class="bg-gray-200 p-2 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-7.036 2.036h-1a2 2 0 00-2 2v1h4v-1a2 2 0 00-2-2z" />
                            </svg>
                        </button>
                        <!-- Delete Button -->
                        <button @click="deleteRule(rule.id)" class="bg-gray-200 p-2 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";

export default {
    components: { AuthenticatedLayout, NavLink },
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
                actions: rule.actions // Ensure this includes the correct actions
            };

            // Make an Axios PUT request to update the page rule
            axios.put(route('domains.pagerules.update', { domainId: domain.id, ruleId: rule.id }), payload);

        }

        function editRule(id) {
            // Логика редактирования правила
            window.location.href = route('domains.pagerules.edit', id);
        }

        function deleteRule(id) {
            if (confirm('Are you sure you want to delete this page rule?')) {
                form.delete(route('pagerules.destroy', id), {
                    onFinish: () => window.location.reload(),
                });
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
