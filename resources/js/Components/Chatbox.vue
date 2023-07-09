<script setup>
import { usePage } from '@inertiajs/vue3';
import { useEventBus } from '@/Services/EventBus';
import { ref , onMounted } from 'vue';
import { getChatMessages , sendChatMessage , updateMessage , deleteMessage } from '@/Services/Request';
import ChatBubble from './ChatBubble.vue';
import DropFileUpload from './DropFileUpload.vue';
import FileListPreview from './FileListPreview.vue';
const user = usePage().props.auth.user;
const eventBus = useEventBus();
let selectedFriend = ref(null);
let selectedChat = ref(null);
let conversationArray = ref([]);
let chatMessage = ref("");
let chatEditMode = ref(false);
let mediafiles = ref([]);
let uploadfiles = ref([]);

onMounted(() => {
    window.Echo.private('private-chat.' + user.id).listen('MessageNotification', (event) => {
        console.log(event)
        if (!event.message.method) {
            if (event.sender_id == selectedFriend.value.id) {
                conversationArray.value.push(event.message.chat);
                scrollToBottom();
            }
        } else {
            switch (event.message.method) {
                case "updateChat":
                    alterChatMessage (event.message.chatId, 1, event.message.message);
                    break;
                case "deleteChat":
                    alterChatMessage (event.message.chatId, 0);
                    break;
            }
        }
    });
})

const selFriend = async (friend) => {
    selectedFriend.value = friend;
    uploadfiles.value = [];
    mediafiles.value = [];

    let resArray = await getChatMessages(user.id, friend.id);
    
    conversationArray.value = resArray.data.messages;

    scrollToBottom();
    /*conversationArray = conversations.filter(item => 
        ((item.sender_id === friend.index) && (item.receiver_id === user.id)) || ((item.sender_id === user.id) && (item.receiver_id === friend.index)));
    */
}

const scrollToBottom = () => {
    setTimeout(() => {
        const chatground = document.querySelector("#chat-main-ground");
        chatground.scrollTop = chatground.scrollHeight;
    },100);
}

const sendMessage = async () => {
    let messageObject = {
        message : chatMessage.value,
        receiver_id : selectedFriend.value.id,
        sender_id : user.id,
    }
    if (!chatEditMode.value) {
        if (uploadfiles.value.length > 0) {
            let files = new FormData();

            uploadfiles.value.forEach ( (file , index) => {
                files.append(`files[${index}]`, file);
            })

            files.append('messageObject', JSON.stringify(messageObject));
            messageObject = files;
        }
        let chat = await sendChatMessage(user.id, selectedFriend.value.id, messageObject);
        conversationArray.value.push (chat.data.chat);
        uploadfiles.value = [];
        mediafiles.value = [];
        onRemoveFiles();
        scrollToBottom();
    } else {
        await updateMessage(selectedChat.value.id, chatMessage.value);
        chatEditMode.value = !chatEditMode.value;
        alterChatMessage(selectedChat.value.id, 1, chatMessage.value);
        selectedChat.value = null;
    }
    chatMessage.value = "";
}

const handleEnterKey = (event) => {
    // Check if the Shift key is pressed
    if (event.shiftKey) {
        event.preventDefault(); // Prevent new line creation
        sendMessage();
    }
}

const deleteChat = async ( chat ) => {
    if (confirm('Are you sure you want to remove this message?')) {
        await deleteMessage (chat.id);
        alterChatMessage(chat.id, 0);
    }
}

const updateChat = (chat) => {
    chatEditMode.value = true;
    selectedChat.value = chat;
    chatMessage.value = chat.message; 
}

const alterChatMessage = (chatId, type, chatMessage) => {
    let indexChat = conversationArray.value.findIndex(x => x.id == chatId);
    if (type==1) {
        conversationArray.value[indexChat].message = chatMessage;
    } else {
        conversationArray.value.splice(indexChat, 1);
    }
}

const onDropFiles = ( ) => {
    let chatContainer = document.getElementById('chat-main-ground');
        chatContainer.style.height = "70%";
}

const onRemoveFiles = ( ) => {
    let chatContainer = document.getElementById('chat-main-ground');
        chatContainer.style.height = "85%";
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
            <DropFileUpload :mediafiles="mediafiles" :uploadfiles="uploadfiles" :onDropFiles="onDropFiles" :filelistContainer="'#chat-file-ground'" v-if="selectedFriend">
                <div id="chat-main-ground">
                    <ChatBubble :deleteChat="deleteChat" :updateChat="updateChat" v-for="(chat, index) in conversationArray" :key="index" :chat="chat" :user="user"></ChatBubble>
                </div>
                <div id="chat-file-ground" v-if="mediafiles.length > 0">
                    <FileListPreview :mediafiles="mediafiles" :uploadfiles="uploadfiles" :onRemoveFiles="onRemoveFiles"/>
                </div>
                <div id="chat-send-ground">
                    <div style="width:80%;float:left;">
                        <textarea placeholder="shift+enter to send" @keydown.enter.prevent="handleEnterKey" v-model="chatMessage" style="height:8.9vh;width:100%;resize:none;"></textarea>
                    </div>
                    <div style="width:20%;float:right;">
                        <button @click="sendMessage" id="send-chat">send</button>
                    </div>
                </div>
            </DropFileUpload>
            
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
        background-color: rgb(211 211 206 / 50%);
        padding: 3%;
    }

    #chat-main-ground {
        height: 85%;
        max-height: 85%;
        overflow-y: scroll;
        padding:2%;
        scroll-behavior: smooth;
    }

    #chat-file-ground {
        height:15%;
        width: 100%;
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