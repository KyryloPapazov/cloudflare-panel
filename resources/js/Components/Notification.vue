<!-- resources/js/Components/Notification.vue -->
<template>
    <transition name="fade">
        <div
            v-if="visible"
            :class="[
        'fixed top-5 right-5 z-50 max-w-xs w-full p-4 rounded-lg shadow-lg',
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
      ]"
        >
            <div class="flex justify-between items-center">
                <p class="font-semibold">{{ message }}</p>
                <button @click="closeNotification" class="text-white hover:text-gray-200">
                    ✕
                </button>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: {
        message: String,
        type: {
            type: String,
            default: 'success', // 'success' или 'error'
        },
    },
    data() {
        return {
            visible: true,
        };
    },
    methods: {
        closeNotification() {
            this.visible = false;
        },
    },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>
