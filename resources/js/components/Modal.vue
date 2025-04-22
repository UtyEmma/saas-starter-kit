<script setup lang="ts">
import { computed } from 'vue';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from './ui/dialog';

const props = defineProps({
    description: {

    },
    title: {

    },
    class: {
        type: String,
        default: 'sm:max-h-[85vh] sm:w-[450px]'
    }
})

const className = computed(() => props.class)
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <slot name="trigger" />
        </DialogTrigger>

        <DialogContent :class="className">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription>{{ description }}</DialogDescription>
            </DialogHeader>

            <slot />
        </DialogContent>
    </Dialog>
</template>