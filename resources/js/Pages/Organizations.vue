<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps({
    organizations: Array,
});

const toggleMembership = (orgId) => {
    router.post(route('organizations.toggle', orgId), {}, {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout title="Organizations">
        <template #header>
            <h2 class="font-semibold text-xl text-ustp-blue leading-tight">
                Discover Organizations
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div 
                        v-for="org in organizations" 
                        :key="org.id" 
                        class="bg-white overflow-hidden shadow-md sm:rounded-lg flex flex-col justify-between border-t-4"
                        :class="org.college ? 'border-ustp-blue' : 'border-ustp-gold'"
                    >
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ org.abbreviation }}</h3>
                                    <p class="text-xs font-semibold text-ustp-gold uppercase tracking-wider mt-1">
                                        {{ org.college ? org.college.abbreviation : 'University-Wide' }}
                                    </p>
                                </div>
                            </div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">{{ org.name }}</h4>
                            <p class="text-sm text-gray-500 line-clamp-3">{{ org.description }}</p>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                            <button 
                                @click="toggleMembership(org.id)"
                                class="px-4 py-2 text-sm font-bold rounded-md transition-colors shadow-sm"
                                :class="org.is_member 
                                    ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' 
                                    : 'bg-ustp-blue text-white hover:bg-ustp-gold hover:text-ustp-blue'"
                            >
                                {{ org.is_member ? 'Leave Organization' : 'Join Organization' }}
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>