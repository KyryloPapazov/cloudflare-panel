<template>
    <AuthenticatedLayout>
        <div class="max-w-3xl mx-auto mt-10">
            <h1 class="text-2xl font-bold mb-6">Edit Page Rule for {{ domain.name }}</h1>
            <form @submit.prevent="submit" class="bg-white p-6 rounded-lg shadow-md">
                <!-- Target URL Input Field -->
                <div class="mb-4">
                    <label for="target_url" class="block text-sm font-medium text-gray-700 mb-1">Target URL</label>
                    <input v-model="form.target_url" type="text" class="w-full border border-gray-300 rounded-md p-2" required placeholder="example.com/*" />
                </div>

                <!-- Dynamic Settings for Actions -->
                <div v-for="(setting, index) in selectedSettings" :key="index" class="mb-4 border border-gray-200 p-4 rounded-lg">
                    <label for="settingType" class="block text-sm font-medium text-gray-700 mb-1">Pick a Setting</label>
                    <select v-model="setting.settingType" class="w-full border border-gray-300 rounded-md p-2 mb-3">
                        <option value="SSL">SSL</option>
                        <option value="Disable Apps">Disable Apps</option>
                        <option value="IP Geolocation Header">IP Geolocation Header</option>
                    </select>

                    <!-- SSL Settings -->
                    <div v-if="setting.settingType === 'SSL'" class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select SSL/TLS encryption mode</label>
                        <select v-model="setting.ssl" class="w-full border border-gray-300 rounded-md p-2">
                            <option value="off">Off</option>
                            <option value="flexible">Flexible</option>
                            <option value="full">Full</option>
                            <option value="strict">Full (strict)</option>
                        </select>
                    </div>

                    <!-- Disable Apps Settings -->
                    <div v-if="setting.settingType === 'Disable Apps'" class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Apps are disabled</label>
                    </div>

                    <!-- IP Geolocation Header Settings -->
                    <div v-if="setting.settingType === 'IP Geolocation Header'" class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">IP Geolocation Header</label>
                        <div class="flex items-center">
                            <input type="checkbox" v-model="setting.ipGeolocation" class="h-4 w-4 text-blue-600 border-gray-300 rounded mr-2" id="geolocationCheckbox"/>
                            <label for="geolocationCheckbox" class="text-sm text-gray-700">Enable IP Geolocation Header</label>
                        </div>
                    </div>
                    <button type="button" @click="removeSetting(index)" class="mt-2 bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">Remove Setting</button>
                </div>

                <!-- Button to add more settings -->
                <button type="button" class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded hover:bg-gray-300 mb-4" @click="addSetting">+ Add a Setting</button>

                <!-- Status Field -->
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" v-model="form.status" class="w-full border border-gray-300 rounded-md p-2">
                        <option value="active">Active</option>
                        <option value="disabled">Disabled</option>
                    </select>

                </div>

                <!-- Submit and Cancel Buttons -->
                <div class="flex justify-between">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Update Page Rule</button>
                    <button type="button" @click="goBack" class="bg-gray-100 text-gray-700 py-2 px-4 rounded hover:bg-gray-200">Cancel</button>

                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    components: { AuthenticatedLayout },
    props: {
        domain: Object,
        pageRule: Object,
    },
    methods: {
        goBack() {
            window.history.back();
        }
    },
    setup(props) {
        const form = useForm({
            target_url: props.pageRule.target_url,
            actions: props.pageRule.actions || [],
            status: props.pageRule.status,
        });

        // Convert existing actions into a format that is compatible with the dynamic form
        const selectedSettings = ref(form.actions.map(action => {
            let setting = {};
            switch (action.id) {
                case 'ssl':
                    setting = { settingType: 'SSL', ssl: action.value || 'off' };
                    break;
                case 'disable_apps':
                    setting = { settingType: 'Disable Apps', appsDisabled: true };
                    break;
                case 'ip_geolocation':
                    setting = { settingType: 'IP Geolocation Header', ipGeolocation: action.value === 'on' };
                    break;
                default:
                    break;
            }
            return setting;
        }));

        function addSetting() {
            selectedSettings.value.push({
                settingType: 'SSL',
                ssl: 'off',
                ipGeolocation: false,
            });
        }

        function removeSetting(index) {
            selectedSettings.value.splice(index, 1);
        }

        function submit() {
            // Convert settings to Cloudflare-compatible format
            const formattedActions = selectedSettings.value.map(setting => {
                if (setting.settingType === 'SSL') {
                    return { id: 'ssl', value: setting.ssl };
                } else if (setting.settingType === 'Disable Apps') {
                    return { id: 'disable_apps', value: null };
                } else if (setting.settingType === 'IP Geolocation Header') {
                    return { id: 'ip_geolocation', value: setting.ipGeolocation ? 'on' : 'off' };
                }
                return null;  // Fallback for safety, should not hit this case
            }).filter(action => action !== null);  // Remove any null actions

            form.actions = formattedActions;

            form.put(route('domains.pagerules.update', [props.domain.id, props.pageRule.id]));
        }

        return {
            form,
            selectedSettings,
            addSetting,
            submit,
            removeSetting,
        };
    },
};
</script>
