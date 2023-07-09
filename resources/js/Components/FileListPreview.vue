<script setup>
const props = defineProps({
    mediafiles: {
        type: Array,
    },
    uploadfiles: {
        type: Array,
    },
    onRemoveFiles : {
        type: Function,
    }
})

const remove = (index) => {
    props.mediafiles.splice(index, 1)
    props.uploadfiles.splice(index, 1)
    if (props.mediafiles.length == 0) {
        props.onRemoveFiles();
    }
}

</script>
<template>
    <div id="file-list-container">
        <div id="image-preview" v-for="(media, index) in mediafiles" :bind="index" @click="remove(index)">
            <img :src="media.preview" />
        </div>
    </div>
</template>
<style scoped>
    #file-list-container {
        background-color: #ccc;
        height:100%;
        width:100%;
        padding: 1%;
        overflow-x: auto;
    }

    #image-preview {
        width:10%;
        height: 100%;
        float:left;
        padding-left:1%;
    }

    #image-preview img {
        width:100%;
        height: 100%;
    }
</style>