<script setup lang="ts">
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from './ui/alert-dialog'


const props = defineProps({
    title: {
        type: String,
        required: true,
        default: "Are you sure?"
    },
    description: {
        type: String,
        default: "Are you sure you wish to proceed with this action? Note: Deleting it will not restore your used gallery count."

    },
    onSuccess: {
        type: Function,
        default: null
    },
    processing: {
        type: Boolean,
        default: false
    },
    success: {
        default: 'Proceed'
    }
})

const onClick = async () => {
    props.onSuccess()
}

</script>

<template>

    <AlertDialog  >
        <AlertDialogTrigger as-child>
            <slot />
        </AlertDialogTrigger>
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>{{ title }}</AlertDialogTitle>
                <AlertDialogDescription>{{ description }}</AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Cancel</AlertDialogCancel>
                <AlertDialogAction @click="onClick" >{{success}}</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>