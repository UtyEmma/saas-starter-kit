<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue';
import Button from '@/components/ui/button/Button.vue';
import Swal from '@/components/Swal.vue';
import PricingModal from './pricing-modal.vue';
import Card from '@/components/ui/card/Card.vue';
import { router, usePage } from '@inertiajs/vue3';

const {auth, subscription} = usePage().props as any

</script>

<template>
    <Card class="p-4 space-y-5" >
        <div class="flex items-center justify-between" >
            <div class="space-y-1">
                <div class="flex space-x-2 items-center ">
                    <h3 class="font-semibold text-lg tracking-tight">{{auth.user.plan?.name}} </h3>
                    <Badge variant="secondary" class="rounded" >
                        {{ subscription.status_label }}
                    </Badge>        
                </div>

                <p class="text-sm">{{ auth.user.plan.description }}</p>
                <!-- <p class="#">Active until <span class="font-medium">{{ subscription.end_date }}</span></p> -->
            </div>

            <div>
                <div class="space-x-2">
                    <Swal title="Are you sure you want to cancel your subscription?" description="This will turn off auto-renewal. Your subscription will remain active until the end of the current billing period but will not renew afterward." success="Yes, Proceed" :onSuccess="() => router.visit(route('billing.cancel'))" >
                        <Button size="sm" variant="outline" >
                            Cancel Subscription
                        </Button>
                    </Swal>
        
                    <PricingModal >
                        <template #trigger>
                            <Button size="sm" >Upgrade Plan</Button>
                        </template>
                    </PricingModal>
                </div>
            </div>
        </div>
    </Card>
</template>