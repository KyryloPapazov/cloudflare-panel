<script>
import NavLink from "@/Components/NavLink.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    components: {AuthenticatedLayout, NavLink},
    props: {
        account: Object,
    },
};
</script>

<style scoped>
.btn {
    @apply inline-block px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75;
}

.btn-secondary {
    @apply inline-block px-4 py-2 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75;
}
</style>

<template>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-medium text-gray-800 mb-4">Account Details</h1>
            <p class="mb-2"><strong>Name:</strong> {{ account.name }}</p>
            <p class="mb-2"><strong>Email:</strong> {{ account.email }}</p>
            <p class="mb-4"><strong>API Key:</strong> {{ account.api_key }}</p>
            <button type="button" @click="$emit('close')" class="btn btn-secondary">Cancel</button>

        </div>
    </div>
</template>
