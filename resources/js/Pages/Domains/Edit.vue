<template>
    <AuthenticatedLayout>
        <div>
            <h1>Edit Domain</h1>
            <form @submit.prevent="submit">
                <div class="form-group">
                    <label for="ssl_mode">SSL Mode</label>
                    <select v-model="form.ssl_mode" class="form-control">
                        <option value="off">Off</option>
                        <option value="flexible">Flexible</option>
                        <option value="full">Full</option>
                        <option value="full_strict">Full (Strict)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update SSL Mode</button>
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
    },
    setup(props) {
        const form = useForm({
            ssl_mode: props.domain.ssl_mode,
        });

        function submit() {
            form.put(route('domains.update', props.domain.id));
        }

        return { form, submit };
    },
};
</script>
