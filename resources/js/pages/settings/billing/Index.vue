<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import Pricing from '@/partials/pricing/pricing.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Billing',
        href: route('billing'),
    },
];

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Password settings" />

        <div class="px-4 py-6 space-y-5">
            <Heading title="Billing" description="Whatever your status, our offers evolve according to your needs." />

            <div>
                <Pricing />
            </div>
        </div>
    </AppLayout>
</template>