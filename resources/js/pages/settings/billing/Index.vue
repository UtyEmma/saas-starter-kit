<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import Pricing from '@/partials/pricing/pricing.vue';
import Card from '@/components/ui/card/Card.vue';
import { usePage } from '@inertiajs/vue3';
import Badge from '@/components/ui/badge/Badge.vue';
import Button from '@/components/ui/button/Button.vue';
import Currency from '@/components/Currency.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Billing',
        href: route('billing'),
    },
];

const {auth, subscription} = usePage().props as any
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Password settings" />

        <div class="px-4 py-6 space-y-5">
            <Heading title="Billing" description="Whatever your status, our offers evolve according to your needs." />

            <div>
                <Card class="p-4 space-y-4" >
                    <div class="flex justify-between" >
                        <div class="space-y-2">
                            <div class="flex space-x-2 items-center ">
                                <h3 class="font-semibold text-lg tracking-tight">{{auth.user.plan?.name}} </h3>
                                <Badge variant="secondary" class="rounded" >
                                    {{ subscription.status_label }}
                                </Badge>        
                            </div>

                            <p class="text-[15px]">Active until {{ subscription.end_date }}</p>
                        </div>

                        <div>
                            <span class="font-bold text-2xl"><Currency />{{ subscription.price?.amount }}</span> 
                        </div>
                    </div>

                    <div class="justify-between flex">
                        <div>

                        </div>

                        <div class="space-x-2">
                            <Button class="" variant="secondary" >Cancel Subscription</Button>
                            <Button>Upgrade Plan</Button>
                        </div>
                    </div>
                </Card>
            </div>

            <div>
                <Pricing />
            </div>
        </div>
    </AppLayout>
</template>