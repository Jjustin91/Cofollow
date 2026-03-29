<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in - USTP CoFollow" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <!-- USTP Custom Header -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-ustp-blue dark:text-white">Welcome Back</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Log in to your USTP CoFollow account</p>
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="University Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    placeholder="juandelacruz@ustp.edu.ph"
                    class="mt-1 block w-full focus:border-ustp-blue focus:ring-ustp-blue"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full focus:border-ustp-blue focus:ring-ustp-blue"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="route().has('password.request')" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-ustp-gold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ustp-blue">
                    Forgot your password?
                </Link>

                <PrimaryButton class="ms-4 bg-ustp-blue hover:bg-ustp-gold focus:bg-ustp-gold" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>