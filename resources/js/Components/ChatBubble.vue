<script setup>

defineProps ({
    chat : {
        type: Object,
    },
    user : {
        type: Object,
    }
})
</script>

<template>
    <div  class="chat">
        <div :class="user.id === chat.sender_id ? 'chat-bubble other-chat' : 'chat-bubble mine-chat'">
            {{ chat.message }}
            <br>
            <!--<i style="font-size:10px;">{{ chat.date }}</i>-->
        </div>
    </div>
</template>

<style scoped>
.chat {
    width:100%;
    display: inline-block;
}
.chat-bubble {
    width:50%;
    padding:2%;
}

.mine-chat {
    background: rgb(96, 225, 96);
    color:white;
    float: left;  
}

.other-chat {
    background: aquamarine;
    color: black;
    float:right;
}
</style>