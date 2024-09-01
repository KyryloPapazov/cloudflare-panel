<template>
    <div class="p-6 bg-white rounded-lg shadow-md max-w-md mx-auto">
        <h1 class="text-xl font-semibold text-gray-800 mb-4">Edit Domain</h1>
        <form @submit.prevent="submit" class="space-y-4">
            <!-- Form Group for SSL Mode -->
            <div>
                <label for="ssl_mode" class="block text-sm font-medium text-gray-700 mb-1">SSL Mode</label>
                <select v-model="form.ssl_mode" id="ssl_mode" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="off">Off</option>
                    <option value="flexible">Flexible</option>
                    <option value="full">Full</option>
                    <option value="strict">Full (Strict)</option>
                </select>
            </div>

            <!-- Buttons for Submit and Cancel -->
            <div class="flex justify-end space-x-3">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Update SSL Mode
                </button>
                <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md shadow hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Cancel
                </button>
            </div>
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
