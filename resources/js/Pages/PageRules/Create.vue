<template>
    <Head title="Create Rule" />
    <AuthenticatedLayout>

        <div class="max-w-3xl mx-auto mt-10">
            <h1 class="text-2xl font-bold mb-6">Add Page Rule for {{ domain.name }}</h1>
            <form @submit.prevent="submit" class="bg-white p-6 rounded-lg shadow-md">
                <!-- URL Input Field -->
                <div class="mb-4">
                    <label for="target_url" class="block text-sm font-medium text-gray-700 mb-1">Target URL</label>
                    <input v-model="form.target_url" type="text" class="w-full border border-gray-300 rounded-md p-2" required placeholder="example.com/*" />
                </div>

                <!-- Dropdowns for selecting settings -->
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

                    <!-- Button to remove the setting -->
                    <button type="button" @click="removeSetting(index)" class="mt-2 bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">Remove Setting</button>
                </div>

                <!-- Button to add more settings -->
                <button type="button" class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded hover:bg-gray-300 mb-4" @click="addSetting">+ Add a Setting</button>

                <!-- Submit and Cancel Buttons -->
                <div class="flex justify-between">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Add Page Rule</button>
                    <button type="button" @click="goBack" class="bg-gray-100 text-gray-700 py-2 px-4 rounded hover:bg-gray-200">Cancel</button>

                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import {Head, useForm} from '@inertiajs/vue3';
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    components: {Head, AuthenticatedLayout },
    props: {
        domain: Object,
    },
    methods: {
        goBack() {
            window.history.back();
        }
    },
    setup(props) {
        const form = useForm({
            target_url: '',
            actions: [],  // Now actions will be an array
        });

        const selectedSettings = ref([
            {
                settingType: 'SSL',
                ssl: 'off',
                ipGeolocation: false,
            },
        ]);

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

        function formatAction(setting) {
            const formattedAction = {
                id: '',
                value: null
            };

            // Ensure settingType is properly handled
            if (!setting.settingType) {
                console.error('Invalid setting: missing settingType', setting);
                return null;
            }

            // Map settingType to id and value for Cloudflare API
            switch (setting.settingType) {
                case 'SSL':
                    formattedAction.id = 'ssl';
                    formattedAction.value = setting.ssl;
                    break;
                case 'Disable Apps':
                    formattedAction.id = 'disable_apps';
                    formattedAction.value = null;
                    break;
                case 'IP Geolocation Header':
                    formattedAction.id = 'ip_geolocation';
                    formattedAction.value = setting.ipGeolocation ? 'on' : 'off';
                    break;
                default:
                    console.error('Invalid settingType encountered:', setting.settingType);
                    return null;
            }
            return formattedAction;
        }

        function submit() {
            // Convert settings to Cloudflare-compatible format
            const formattedActions = selectedSettings.value
                .map(formatAction)
                .filter(action => action !== null);  // Remove any null actions

            console.log('Formatted Actions being sent:', formattedActions);

            form.actions = formattedActions;

            form.post(route('domains.pagerules.store', props.domain.id), {
                onSuccess: () => {
                    form.reset();
                    selectedSettings.value = [];  // Clear settings after successful submission
                    this.$emit('close');
                },
            });
        }

        return {form, selectedSettings, addSetting, removeSetting, submit};
    },
};
</script>
