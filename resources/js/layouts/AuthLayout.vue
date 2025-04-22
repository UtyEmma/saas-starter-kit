<script setup lang="ts">
import Toast from '@/components/Toast.vue';
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <AuthLayout :title="title" :description="description">
        <slot />

        <Toast />
    </AuthLayout>
</template>
