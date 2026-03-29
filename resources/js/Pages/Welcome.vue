<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Welcome to CoFollow" />

    <div class="min-h-screen bg-gray-100 selection:bg-ustp-gold selection:text-ustp-blue">
        
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <ApplicationMark class="block h-9 w-auto text-ustp-blue" />
                        <span class="ml-3 font-bold text-xl text-ustp-blue">USTP CoFollow</span>
                    </div>
                    
                    <div v-if="canLogin" class="flex items-center space-x-4">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-sm font-semibold text-ustp-blue hover:text-ustp-gold">
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link :href="route('login')" class="text-sm font-semibold text-ustp-blue hover:text-ustp-gold">
                                Log in
                            </Link>
                            <Link v-if="canRegister" :href="route('register')" class="bg-ustp-blue text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-ustp-gold hover:text-ustp-blue transition">
                                Register
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex flex-col items-center justify-center pt-20 pb-12 px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-extrabold text-ustp-blue tracking-tight sm:text-6xl">
                The Campus Network for <br>
                <span class="text-ustp-gold">USTP Trailblazers</span>
            </h1>
            
            <p class="mt-6 text-xl text-gray-600 max-w-2xl mx-auto">
                Discover events, join student organizations, and connect with your college community in one centralized platform exclusively for Cagayan de Oro students.
            </p>

            <div class="mt-10 flex justify-center gap-4">
                <Link :href="route('register')" class="bg-ustp-gold text-ustp-blue font-bold text-lg px-8 py-3 rounded-lg shadow-lg hover:bg-yellow-400 transition transform hover:-translate-y-1">
                    Join the Network
                </Link>
                <Link :href="route('login')" class="bg-white text-ustp-blue font-bold border border-ustp-blue text-lg px-8 py-3 rounded-lg shadow hover:bg-gray-50 transition">
                    Sign In
                </Link>
            </div>
            
            <div class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto text-left">
                <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-ustp-gold">
                    <h3 class="text-xl font-bold text-ustp-blue">College Events</h3>
                    <p class="mt-2 text-gray-600">Never miss a general assembly or department week. Stay updated with your specific college calendar.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-ustp-blue">
                    <h3 class="text-xl font-bold text-ustp-blue">Organizations</h3>
                    <p class="mt-2 text-gray-600">Browse and join recognized student clubs, from tech communities to performing arts.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-ustp-gold">
                    <h3 class="text-xl font-bold text-ustp-blue">Smart Alerts</h3>
                    <p class="mt-2 text-gray-600">Get SMS and email notifications straight from your organization officers and the SSC.</p>
                </div>
            </div>
        </main>
    </div>
</template>