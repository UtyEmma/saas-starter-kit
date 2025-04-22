<script setup lang="ts">
import Modal from '@/components/Modal.vue';
import Pricing from '@/partials/pricing/pricing.vue';

</script>

<template>
    <Modal class="max-w-6xl h-[95%]" title="Choose your plan" description="Whatever your status, our offers evolve according to your needs." >
        <template #trigger>
            <slot name="trigger" />
        </template>

        <div >
            <Pricing />
        </div>
    </Modal>
</template>