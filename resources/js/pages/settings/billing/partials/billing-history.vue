<script setup lang="ts">
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { usePage } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';

const { subscriptions } = usePage().props as any

</script>

<template>
    <Card class="p-4" >
        <Heading title="Billing History"  />
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Reference</TableHead>
                    <TableHead>Plan</TableHead>
                    <TableHead>Auto Renews</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Expires At</TableHead>
                </TableRow>
            </TableHeader>

            <TableBody class="divide-y">
                <TableRow v-for="subscription in subscriptions" :key="subscription.id">
                    <TableCell>{{ subscription.reference }}</TableCell>
                    <TableCell>{{ subscription.plan.name }} Plan</TableCell>
                    <TableCell>
                        
                    </TableCell>
                    <TableCell>
                        <Badge variant="secondary" size="lg" >{{ subscription.status }}</Badge>
                    </TableCell>
                    <TableCell>{{ subscription.end_date }}</TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </Card>
</template>