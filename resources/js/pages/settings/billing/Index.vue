<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import SubscriptionInfo from './partials/subscription-info.vue';
import BillingHistory from './partials/billing-history.vue';


const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('home')
    },
    {
        title: 'Billing',
        href: route('billing'),
    },
];

const { auth } = usePage().props as any

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Billing" />

        <div class="px-4 py-6 space-y-5">
            <Heading title="Billing" description="Whatever your status, our offers evolve according to your needs." />

            <div>
                <SubscriptionInfo v-if="auth.user?.plan" />
            </div>

            <div>
                <BillingHistory />
            </div>
        </div>
    </AppLayout>
</template>