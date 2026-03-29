<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3'; // Combined import!

const props = defineProps({
    events: Array,
    tasks: Array,
    myOrgs: Array,
    announcements: Array, // Accept the announcements array
});

const formatEventDate = (dateString) => {
    const options = { weekday: 'short', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString('en-US', options);
};

// Task logic
const newTaskTitle = ref('');

const addTask = () => {
    if (newTaskTitle.value.trim() === '') return;
    router.post(route('tasks.store'), { title: newTaskTitle.value }, {
        preserveScroll: true,
        onSuccess: () => newTaskTitle.value = '', 
    });
};

const toggleTask = (task) => {
    router.put(route('tasks.update', task.id), { is_completed: !task.is_completed }, {
        preserveScroll: true,
    });
};

// Calculate progress bar width
const progressPercentage = computed(() => {
    if (!props.tasks || props.tasks.length === 0) return 0;
    const completed = props.tasks.filter(t => t.is_completed).length;
    return Math.round((completed / props.tasks.length) * 100);
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-ustp-blue leading-tight">
                Student Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Welcome Banner -->
                <div class="bg-ustp-blue overflow-hidden shadow-xl sm:rounded-lg mb-6 p-6 flex items-center gap-6">
                    <!-- Dynamic Profile Picture -->
                    <img 
                        :src="$page.props.auth.user.profile_photo_url" 
                        :alt="$page.props.auth.user.name" 
                        class="h-24 w-24 rounded-full object-cover border-4 border-ustp-gold shadow-md"
                    >
                    <!-- Welcome Text -->
                    <div>
                        <h1 class="text-3xl font-bold text-white">
                            Welcome back, {{ $page.props.auth.user.name.split(' ')[0] }}!
                        </h1>
                        <p class="text-ustp-gold mt-2 font-medium">
                            <span v-if="$page.props.auth.user.program">
                                {{ $page.props.auth.user.program.abbreviation }} Trailblazer | {{ $page.props.auth.user.college.abbreviation }}
                            </span>
                            <span v-else>
                                <a :href="route('profile.show')" class="underline hover:text-white transition">Complete your profile to select your program &rarr;</a>
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Widgets Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Calendar Widget -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-t-4 border-ustp-gold">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-ustp-blue">Upcoming Events</h3>
                            <button class="text-sm text-gray-500 hover:text-ustp-blue">View Calendar &rarr;</button>
                        </div>
                        <ul class="divide-y divide-gray-200">
                            <li v-for="event in events" :key="event.id" class="py-3">
                                <p class="text-sm font-medium text-gray-900">{{ event.title }}</p>
                                <p class="text-sm text-gray-500">{{ formatEventDate(event.event_date) }} - {{ event.location }}</p>
                            </li>
                            
                            <li v-if="events.length === 0" class="py-3 text-sm text-gray-500">
                                No upcoming events for your college.
                            </li>
                        </ul>
                    </div>

                    <!-- To-Do List Widget -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-t-4 border-ustp-blue">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-ustp-blue">My To-Do List</h3>
                        </div>
                        
                        <!-- Dynamic Progress Bar -->
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Progress</span>
                            <span>{{ progressPercentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-6">
                            <div class="bg-ustp-blue h-2.5 rounded-full transition-all duration-500" :style="{ width: progressPercentage + '%' }"></div>
                        </div>

                        <!-- Add Task Input -->
                        <form @submit.prevent="addTask" class="mb-4 flex gap-2">
                            <input 
                                v-model="newTaskTitle" 
                                type="text" 
                                placeholder="Add a new task... (press Enter)" 
                                class="w-full text-sm border-gray-300 focus:border-ustp-blue focus:ring-ustp-blue rounded-md shadow-sm"
                            >
                            <button type="submit" class="bg-ustp-gold text-ustp-blue font-bold px-4 py-2 rounded-md hover:bg-yellow-400 transition">
                                +
                            </button>
                        </form>

                        <!-- Task List -->
                        <ul class="space-y-3 max-h-48 overflow-y-auto pr-2">
                            <li v-for="task in tasks" :key="task.id" class="flex items-center group">
                                <input 
                                    type="checkbox" 
                                    :checked="task.is_completed"
                                    @change="toggleTask(task)"
                                    class="w-4 h-4 text-ustp-blue border-gray-300 rounded focus:ring-ustp-gold cursor-pointer"
                                >
                                <span 
                                    class="ml-3 text-sm transition-colors duration-200"
                                    :class="task.is_completed ? 'text-gray-400 line-through' : 'text-gray-700'"
                                >
                                    {{ task.title }}
                                </span>
                            </li>
                            <li v-if="tasks.length === 0" class="text-sm text-gray-500 text-center py-2">
                                You are all caught up!
                            </li>
                        </ul>
                    </div>
                </div> <!-- END Widgets Grid -->

                <!-- My Organizations Widget (Moved OUTSIDE the To-Do list) -->
                <div class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-t-4 border-ustp-gold">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-ustp-blue">My Organizations</h3>
                        <Link :href="route('organizations.index')" class="text-sm font-medium text-gray-500 hover:text-ustp-blue transition">
                            Browse All &rarr;
                        </Link>
                    </div>
                    
                    <ul class="divide-y divide-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <li v-for="org in myOrgs" :key="org.id" class="py-3 flex justify-between items-center border-b md:border-b-0 md:border-r last:border-0 pr-4">
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ org.abbreviation }}</p>
                                <p class="text-xs text-gray-500">{{ org.name }}</p>
                            </div>
                            
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-50 text-ustp-blue uppercase tracking-wider">
                                {{ org.pivot.role }}
                            </span>
                        </li>
                        
                        <li v-if="myOrgs.length === 0" class="col-span-1 md:col-span-2 py-4 text-sm text-gray-500 text-center bg-gray-50 rounded-md">
                            You haven't joined any organizations yet.
                        </li>
                    </ul>
                </div>

                <!-- Organization News Feed -->
                <div class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-t-4 border-ustp-blue">
                    <h3 class="text-lg font-bold text-ustp-blue mb-4">Campus News Feed</h3>
                    
                    <div class="space-y-6">
                        <div v-for="announcement in announcements" :key="announcement.id" class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 text-xs font-bold rounded bg-ustp-gold text-ustp-blue uppercase tracking-wide">
                                    {{ announcement.organization.abbreviation }}
                                </span>
                                <span class="text-xs text-gray-500">{{ formatEventDate(announcement.created_at) }}</span>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900">{{ announcement.title }}</h4>
                            <p class="text-sm text-gray-600 mt-2">{{ announcement.content }}</p>
                            <p class="text-xs text-gray-400 mt-3 font-medium">Posted by {{ announcement.user.name }}</p>
                        </div>
                        
                        <div v-if="announcements.length === 0" class="text-sm text-gray-500 text-center py-6 bg-gray-50 rounded-md border border-dashed border-gray-200">
                            No recent announcements from your organizations. Join some clubs to fill up your feed!
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </AppLayout>
</template>