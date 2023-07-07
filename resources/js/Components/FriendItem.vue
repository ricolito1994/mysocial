<script setup>
import ProfilePic from "@/Assets/Images/profile.png";
import { useEventBus } from '@/Services/EventBus';
import { ref, onMounted } from 'vue';

const eventBus = useEventBus();
let friendItemDOM = '';
let activeFriendId = ref(-1);

onMounted(() => {
    friendItemDOM = document.querySelectorAll('.friend-item-container')
});

defineProps({
    friend: {
        Type:Object
    },
    friendId: {
        Type: String
    }
});



const selectFriend = ( friend, friendId ) => {
    activeFriendId.value = friendId;
    
    for (let i in friendItemDOM) {
        let sel = friendItemDOM[i];
        if (sel.id) {
            if (sel.id == activeFriendId.value) {
                friendItemDOM[i].classList = "friend-item-container selected-friend";
            } else {
                friendItemDOM[i].classList = "friend-item-container";
            }
        }
    }

    eventBus.$emit('selectfriend', friend)
}

</script>
<template>
   <div :id="friendId" class="friend-item-container" @click="selectFriend(friend, friendId)">
        <div :class="friend.status == 1 ? 'online-user' : 'offline-user'" id="friend-profile-picture">
            <img :src="ProfilePic"  />
        </div>
        <div id="friend-profile-details">
            <div><strong>{{ friend.name }}</strong></div>
            <div></div>
        </div>
        <div style="clear:both"></div>
   </div>
</template>
<style scoped>
.friend-item-container {
    width: 95%;
    padding:2%;
    margin-bottom:2%;
    cursor: pointer;
}

.friend-item-container:hover, .selected-friend {
    background-color: #ccc;
}

#friend-profile-picture {
    width:20%;
    border-radius: 50px;
    
    float:left;
}

#friend-profile-picture img {
    width:100%;
    border-radius: 50px;
}

#friend-profile-details {
    width:70%;
    float:right;
}

.online-user {
    border:5px solid aquamarine;
}

.offline-user {
    border:5px solid aquamarine;
}
</style>