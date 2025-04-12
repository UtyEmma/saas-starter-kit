<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Tabs, TabsList, TabsContent, TabsTrigger } from '@/components/ui/tabs'
import { ref } from 'vue';
import Plan from './plan.vue';

const props : any = usePage().props

const timeline = ref(props.pricing[0].shortcode)

</script>

<template>
    <div >
        <Tabs :default-value="timeline" >
            <TabsList class="grid w-[300px] mx-auto grid-cols-2">
                <TabsTrigger class="px-2" v-for="timeline in props.pricing" :key="timeline.shortcode" :value="timeline.shortcode">
                    {{timeline.name}}
                </TabsTrigger>
            </TabsList>

            <TabsContent v-for="timeline in props.pricing" :key="`content-${timeline.shortcode}`" :value="timeline.shortcode">
                <div class="mt-12 flex gap-6 justify-center items-center">
                    <div class="w-1/4" v-for="plan in timeline.plans" :key="plan.id" >
                        <Plan :plan="plan" />
                    </div>
                </div>
            </TabsContent>
        </Tabs>

    </div>
</template>