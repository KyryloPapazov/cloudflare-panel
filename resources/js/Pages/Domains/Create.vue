<template>
    <AuthenticatedLayout>
        <div>

            <h1>Add Domain</h1>
            <form @submit.prevent="submit">
                <div class="form-group">
                    <label for="cloudflare_account_id">Cloudflare Account</label>
                    <select v-model="form.cloudflare_account_id" class="form-control">
                        <option v-for="account in accounts" :key="account.id" :value="account.id">
                            {{ account.name }} ({{ account.email }})
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Domain Name</label>
                    <input type="text" v-model="form.name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Domain</button>
            </form>
        </div>
<!--        <div class="flex space-x-4 mt-6">-->
<!--            <button @click="triggerSuccess" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">-->
<!--                Триггер успеха-->
<!--            </button>-->
<!--            <button @click="triggerError" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">-->
<!--                Триггер ошибки-->
<!--            </button>-->
<!--        </div>-->
    </AuthenticatedLayout>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";


export default {

    components: {AuthenticatedLayout },
    props: {
        accounts: Array,
    },
    setup() {
        const form = useForm({
            cloudflare_account_id: '',
            name: '',
        });

        function submit() {

            form.post(route('cloudflare-domains.store'));
        }
        console.log(submit);
        return { form, submit };
    },
};
</script>
