<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { useEventBus } from '@/Services/EventBus';
import { ref , onMounted } from 'vue';
import { getChatMessages , sendChatMessage } from '@/Services/Request';
import ChatBubble from './ChatBubble.vue';

const user = usePage().props.auth.user;
const eventBus = useEventBus();
let selectedFriend = ref(null);
let conversationArray = ref([]);
let chatMessage = ref("");

onMounted(() => {
    window.Echo.private('private-chat.' + user.id)
    .listen('MessageNotification', (event) => {
        // Handle the received private message event
        conversationArray.value.push(event.message);
    });
})

const selFriend = async (friend) => {
    selectedFriend.value = friend;

    let resArray = await getChatMessages(user.id, friend.id);
    
    conversationArray.value = resArray.data.messages;
    
    /*conversationArray = conversations.filter(item => 
        ((item.sender_id === friend.index) && (item.receiver_id === user.id)) || ((item.sender_id === user.id) && (item.receiver_id === friend.index)));
    */
}

const sendMessage = async () => {
    let messageObject = {
        message : chatMessage.value,
        receiver_id : selectedFriend.value.id,
        sender_id : user.id,
    }
    //console.log(selectedFriend.value.id)
    await sendChatMessage(user.id, selectedFriend.value.id, messageObject);
    conversationArray.value.push (messageObject);
    chatMessage.value = "";
}

const handleEnterKey = (event) => {
    // Check if the Shift key is pressed
    if (event.shiftKey) {
        event.preventDefault(); // Prevent new line creation
        sendMessage();
    }
}



eventBus.$on('selectfriend',  selFriend);

</script>

<template> 
    <div id="chat-box-container">
        <div id="chat-user-name"> 
            <strong>{{ selectedFriend ? `Chat ${selectedFriend.name}` : 'CHATBOX' }}</strong>
        </div>
        <div id="chat-ground">
            <div v-if="!selectedFriend">
                <div style="">
                    chats will appear here
                </div>
            </div>
            <div style="height:100%;" v-if="selectedFriend">
                <div id="chat-main-ground">
                    <ChatBubble v-for="chat in conversationArray" v-bind:key="chat.id" :chat="chat" :user="user"></ChatBubble>
                </div>
                <div id="chat-send-ground">
                    <div style="width:80%;float:left;">
                        <textarea placeholder="shift+enter to send" @keydown.enter.prevent="handleEnterKey" v-model="chatMessage" style="height:8.9vh;width:100%;resize:none;"></textarea>
                    </div>
                    <div style="width:20%;float:right;">
                        <button @click="sendMessage" id="send-chat">send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    #chat-user-name {
        height:10%;
        font-size:30px;
    }
    #chat-box-container {
        width:70%;
        height:66vh;
        max-height: 66vh;
        float:right;
        padding-left: 2%;
    }
    #chat-ground {
        height: 90%;
        max-height: 90%;
        background-color: rgb(211 211 206 / 50%);;
    }

    #chat-main-ground {
        height: 85%;
        max-height: 85%;
        overflow-y: scroll;
        padding:2%;
    }

    #chat-send-ground {
        height: 15%;
        width:100%;
    }
    #send-chat {
        width:100%;
        padding:2.9vh;
        background: aquamarine;
        border: 1px solid #ccc
    }
</style>