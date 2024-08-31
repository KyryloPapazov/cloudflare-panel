<!-- resources/js/Pages/PageRules/Edit.vue -->
<template>
    <AuthenticatedLayout>
        <div>
            <h1>Edit Page Rule for {{ domain.name }}</h1>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="target_url" class="block text-gray-700">Target URL</label>
                    <input type="text" id="target_url" v-model="form.target_url" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label for="actions" class="block text-gray-700">Actions</label>
                    <input type="text" id="actions" v-model="form.actions" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-gray-700">Status</label>
                    <select id="status" v-model="form.status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="active">Active</option>
                        <option value="disabled">Disabled</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Page Rule</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    components: { AuthenticatedLayout },
    props: {
        domain: Object,
        pageRule: Object,
    },
    setup(props) {
        const form = useForm({
            target_url: props.pageRule.target_url,
            actions: props.pageRule.actions,
            status: props.pageRule.status,
        });

        function submit() {
            form.put(route('domains.pagerules.update', [props.domain.id, props.pageRule.id]));
        }

        return {
            form,
            submit,
        };
    },
};
</script>
