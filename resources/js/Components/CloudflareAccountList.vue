<script setup>

</script>

<template>
    <div>
        <h1>Cloudflare Accounts</h1>
        <button @click="showAddAccountForm">Add Account</button>
        <ul>
            <li v-for="account in accounts" :key="account.id">
                {{ account.name }} - {{ account.email }}
                <button @click="deleteAccount(account.id)">Delete</button>
            </li>
        </ul>
        <AddAccountForm v-if="isAddingAccount" @add-account="addAccount" @cancel="isAddingAccount = false"/>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import AddAccountForm from './AddAccountForm.vue';

export default {
    components: {
        AddAccountForm,
    },
    data() {
        return {
            isAddingAccount: false,
        };
    },
    computed: {
        ...mapGetters(['allAccounts']),
    },
    methods: {
        ...mapActions(['fetchAccounts', 'deleteAccount']),
        showAddAccountForm() {
            this.isAddingAccount = true;
        },
        async addAccount(accountData) {
            await this.$store.dispatch('addAccount', accountData);
            this.isAddingAccount = false;
        },
    },
    created() {
        this.fetchAccounts();
    },
};
</script>

<style scoped>

</style>
