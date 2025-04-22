<script lang="ts" setup>
import { usePage,  } from '@inertiajs/vue3';
import { watch } from 'vue';
import { useToast } from '@/components/ui/toast/use-toast'
import Toaster from './ui/toast/Toaster.vue';

interface ToastProps extends Record<any, any> {
    toast?: {
        status?: string;
        message?: string;
        title?: string;
    };
}

const {props} = usePage<ToastProps>()

const { toast } = useToast()

watch(props, () => {
    if(props.toast){
        if((props.toast.status as any) == 'error') {
            toast({...props.toast, description: props.toast.message, 'variant': 'destructive'})
        }else{
            toast({...props.toast, description: props.toast.message})
        }
    }
})

</script>

<template>
    <Toaster />
</template>