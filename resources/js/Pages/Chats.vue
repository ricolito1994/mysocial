<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FriendsList from '@/Components/FriendsList.vue';
import Chatbox from '@/Components/Chatbox.vue';
import { Head } from '@inertiajs/vue3';
import { provideEventBus } from '@/Services/EventBus';
import { ref } from 'vue';

defineProps ({
    users : {
        type: Object,
    }
})

provideEventBus();

</script>

<template>
    <Head title="My Messenger" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Messenger</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <FriendsList :users="users"></FriendsList>
                        <Chatbox></Chatbox>
                        <div style="clear:both;"></div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
