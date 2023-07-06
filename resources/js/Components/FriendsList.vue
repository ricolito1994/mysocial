<script setup>
import FriendItem from './FriendItem.vue';

defineProps ({
    users : {
        type: Object,
    }
})

</script>
<template>
    <div id="friend-list">
        <div id="my-friends">
            Room ({{ users.length }})
        </div>
        <div id="friend-list-container">
            <FriendItem v-for="friend in users" v-bind:key="friend.id" :friend="friend" :friendId="friend.id"></FriendItem>
        </div>
    </div>
</template>
<style scoped>
#friend-list {
    height:66vh;
    max-height: 66vh;
    width:30%;
    float:left;
    border-right: 1px solid #ccc;
    overflow-y: auto;
}

#my-friends {
    height: 30px;
    width:30%;
}

#friend-list-container {
    height:95%;
    max-height: 95%;
}
</style>