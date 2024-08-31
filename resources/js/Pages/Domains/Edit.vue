<template>
        <div>
            <h1>Edit Domain</h1>
            <form @submit.prevent="submit">
                <div class="form-group">
                    <label for="ssl_mode">SSL Mode</label>
                    <select v-model="form.ssl_mode" class="form-control">
                        <option value="off">Off</option>
                        <option value="flexible">Flexible</option>
                        <option value="full">Full</option>
                        <option value="strict">Full (Strict)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update SSL Mode</button>
                <button type="button" @click="$emit('close')" class="btn btn-secondary">Cancel</button>
            </form>
        </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    components: { AuthenticatedLayout },
    props: {
        domain: Object,
    },
    setup(props, { emit }) {
        const form = useForm({
            ssl_mode: props.domain.ssl_mode,
        });

        function submit() {
            form.put(route('cloudflare-domains.update', props.domain.id), {
                onSuccess: () => {
                    emit('close');  // Закрываем модальное окно после успешного обновления
                },
            });
        }

        return { form, submit };
    },
};
</script>
