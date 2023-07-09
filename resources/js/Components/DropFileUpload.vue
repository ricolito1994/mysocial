<script setup>
let props = defineProps({
    mediafiles : {
        type: Array,
    },
    uploadfiles : {
        type: Array,
    },
    filelistContainer : {
        type: String,
    },
    onDropFiles: {
        type: Function,
    }
})

const handleDrop = ( event ) => {
    event.preventDefault();
    const files = event.dataTransfer.files;
    processFiles(files);
    props.onDropFiles();
}

const beforeDrop = ( event ) => {
    event.dataTransfer.dropEffect = 'copy';
}

const processFiles = ( files ) => {
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        reader.onload = (event) => {
            const preview = event.target.result;
            props.mediafiles.push({
                name: file.name,
                preview: preview,
            });
        };
        props.uploadfiles.push(file);
        reader.readAsDataURL(file);
    }
} 

const handleFiles = (event) => {
    const files = event.target.files;
    processFiles(files);
}

</script>
<template>
    <div  @drop="handleDrop" @dragover.prevent="beforeDrop" style="height:100%;">
        <input type="file" multiple style="display: none;" ref="fileInput" @change="handleFiles">
        <slot />
        
    </div>
</template>
<style scoped>

</style>