<template>
    <div>
        <h2>Add Cloudflare Account</h2>
        <form @submit.prevent="submitForm">
            <div>
                <label for="name">Name</label>
                <input type="text" v-model="name" required />
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" v-model="email" required />
            </div>
            <div>
                <label for="api_key">API Key</label>
                <input type="text" v-model="apiKey" required />
            </div>
            <button type="submit">Add Account</button>
            <button type="button" @click="$emit('cancel')">Cancel</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            email: '',
            apiKey: '',
        };
    },
    methods: {
        submitForm() {
            const accountData = {
                name: this.name,
                email: this.email,
                api_key: this.apiKey,
            };
            this.$emit('add-account', accountData);
        },
    },
};
</script>
