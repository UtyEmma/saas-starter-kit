<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Tooltip from '@/components/ui/tooltip/Tooltip.vue';
import TooltipContent from '@/components/ui/tooltip/TooltipContent.vue';
import TooltipProvider from '@/components/ui/tooltip/TooltipProvider.vue';
import TooltipTrigger from '@/components/ui/tooltip/TooltipTrigger.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { CheckIcon, InfoIcon } from 'lucide-vue-next';


const props = defineProps({
    plan: {
        required: true,
        type: Object
    }
})

const {auth} = usePage().props as any

console.log(auth);

</script>

<template>
    <div class="flex flex-col border-2 text-center shadow-xl rounded-xl p-8 " :class="{'dark:border-blue-700 border-blue-600': plan.is_popular}" >
        <p class="mb-3" v-if="plan.is_popular" >
            <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-lg text-xs uppercase font-semibold bg-blue-100 text-blue-800 dark:bg-blue-600 dark:text-white">Most popular</span>
        </p>

        <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">{{plan.name}}</h4>
        <span class="mt-5 font-bold text-5xl text-gray-800 dark:text-neutral-200">
            <span class="font-bold text-2xl -me-2">$</span> {{plan.price.amount}}
        </span>
        <p class="mt-2 text-sm text-gray-500 dark:text-neutral-500">{{plan.description}}</p>

        <div class="mt-7 space-y-7">
            <ul class="space-y-2.5 text-sm">
                <li v-for="feature in plan.features" class="flex items-center gap-x-2">
                    <CheckIcon class="text-blue-600 dark:text-blue-500 size-5" />
                    <span class="text-gray-800 dark:text-neutral-400">{{feature.name}}</span>
    
                    <Tooltip>
                        <TooltipTrigger as-child>
                            <InfoIcon class="size-3 cursor-pointer" />
                        </TooltipTrigger>
                        <TooltipContent>
                            <p>{{feature.description}}</p>
                        </TooltipContent>
                    </Tooltip>
                </li>
            </ul>
    

            <Button :disabled="true" variant="outline"  v-if="auth.user?.plan?.id == plan.id" class="w-full" >
                Current Plan
            </Button>

            <Button :as="Link" :href="route('billing.trial', {planPrice: plan.price.id})"  v-else-if="plan.trial_period" class="w-full" >
                Start {{ plan.trial_period }} days Trial
            </Button>
    
            <Button :as="Link" :href="route('billing.checkout', {planPrice: plan.price.id})" v-else class="w-full" >
                Select Plan
            </Button>
        </div>
    </div>
</template>
