<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';

defineProps ({
    chat : {
        type: Object,
    },
    user : {
        type: Object,
    },
    deleteChat : {
        type: Function,
    },
    updateChat : {
        type: Function,
    }
})



</script>

<template>
    <div class="chat">
        <div :class="user.id === chat.sender_id ? 'chat-bubble mine-chat' : 'chat-bubble other-chat'">
            <div>{{ chat.message }}</div>
            <div id="uploaded-files-container">
                <div :class="{
                    'uploaded-file uploaded-file-1' : chat.files.length == 1,
                    'uploaded-file uploaded-file-2' : chat.files.length == 2,
                    'uploaded-file uploaded-file-3' : chat.files.length >= 3,
                }" v-for="(file , index) in chat.files" :key="index">
                    <img :src="file.path" />
                </div>
            </div>
        </div>
        <div :align="user.id === chat.sender_id ? 'right' : 'left'" :class="user.id === chat.sender_id ? 'chat-bubble chat-bubble-options mine-chat-options' : 'chat-bubble chat-bubble-options other-chat-options'">
            <div style="margin-top:-10px;">
                <SecondaryButton @click="deleteChat(chat)" :padding="2" v-if="user.id === chat.sender_id">❌</SecondaryButton>
                <SecondaryButton @click="updateChat(chat)" :padding="2" v-if="user.id === chat.sender_id">✍</SecondaryButton>
            </div>
        </div>
    </div>
</template>

<style scoped>
.chat {
    width:100%;
    display: inline-block;
}

.chat:hover > .chat-bubble-options {
    display: block;
}

.chat-bubble {
    width:50%;
    padding:2%;
}

.mine-chat {
    background: aquamarine;
    color: black;
    float:right;
}

.other-chat {
    background: rgb(96, 225, 96);
    color:white;
    float: left;  
}

.mine-chat-options {
    float:left;
}

.other-chat-options {
    float:left;
}

.chat-bubble-options {
    display:none;
}

#uploaded-files-container {
    width:100%;
}

.uploaded-file {
    height: 100%;
    float:left;
    padding-left: 1%;
}

.uploaded-file-1 {
    width:100%;
    height: 25vh;
}

.uploaded-file-2 {
    width:50%;
    height: 15vh;
}

.uploaded-file-3 {
    width:33.33%;
    height: 10vh;
}

.uploaded-file img {
    width:100%;
    height:100%;
}

</style>