<script lang="ts" setup>
import { usePage,  } from '@inertiajs/vue3';
import { watch } from 'vue';
import { useToast } from '@/components/ui/toast/use-toast'

interface ToastProps extends Record<any, any> {
    toast?: {
        status?: string;
        description?: string;
        title?: string;
    };
}

const { props } = usePage<ToastProps>()

const { toast } = useToast()

watch(props, () => {
    if(props.toast){
        if((props.toast.status as any) == 'error') {
            toast({...props.toast, 'variant': 'destructive'})
        }else{
            toast(props.toast)
        }
    }
})

</script>

<template>
    <span></span>
</template>