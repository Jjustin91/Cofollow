<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    events: Array,
});

const formatEventDate = (dateString) => {
    const options = { weekday: 'long', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString('en-US', options);
};
</script>

<template>
    <AppLayout title="Campus Calendar">
        <template #header>
            <h2 class="font-semibold text-xl text-ustp-blue leading-tight">
                Campus Events Calendar
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-t-4 border-ustp-gold">
                    
                    <div v-if="events.length === 0" class="text-center py-12 text-gray-500">
                        No upcoming events scheduled at this time.
                    </div>

                    <div class="space-y-6">
                        <div v-for="event in events" :key="event.id" class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                            <h3 class="text-xl font-bold text-gray-900">{{ event.title }}</h3>
                            <div class="mt-2 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6 text-sm text-gray-600 font-medium">
                                <div class="flex items-center gap-1">
                                    <svg class="h-4 w-4 text-ustp-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ formatEventDate(event.event_date) }}
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="h-4 w-4 text-ustp-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ event.location }}
                                </div>
                            </div>
                            <p class="mt-3 text-gray-600 text-sm">{{ event.description }}</p>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </AppLayout>
</template>