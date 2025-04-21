<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, defineProps } from 'vue';

interface Props {
    symbol?: string;
}

const props = defineProps<Props>()

const {locale} = usePage().props as any

const currency = computed(() => {
    return props.symbol ?? locale.currency.symbol
})

</script>

<template>
    <span>{{ currency }}</span>
</template>