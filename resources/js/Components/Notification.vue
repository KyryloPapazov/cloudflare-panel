<!-- resources/js/Components/Notification.vue -->
<template>
    <div
        v-if="show"
        :class="[
      'fixed top-5 right-5 px-4 py-3 rounded shadow-lg cursor-pointer z-50 transition-opacity duration-300',
      type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    ]"
        @click="hideNotification"
    >
        {{ message }}
    </div>
</template>

<script>
export default {
    props: {
        message: {
            type: String,
            default: ''
        },
        type: {
            type: String,
            default: 'success' // Тип уведомления по умолчанию
        }
    },
    data() {
        return {
            show: false,
            timeout: null,
        };
    },
    watch: {
        message: {
            immediate: true,
            handler(newValue) {
                if (newValue) {
                    this.show = true; // Показать уведомление
                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(this.hideNotification, 3000); // Скрыть через 3 секунды
                } else {
                    this.hideNotification();
                }
            }
        }
    },
    methods: {
        hideNotification() {
            this.show = false;
            clearTimeout(this.timeout);
        }
    }
};
</script>
