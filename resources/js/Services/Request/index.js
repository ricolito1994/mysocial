import axios from 'axios';

const port = "8000";
const host = "http://127.0.0.1";

export function getChatMessages ( userId, friendId ) {
    return new Promise ( (resolve, reject) => {
        let response =  axios.post(`${host}:${port}/chatMessages`, {
            userId : userId,
            friendId : friendId,
        });

        response.then (function (res) {
            resolve(res);
        })
        .catch(err=>{
            reject(err);
        })

    });
}

export function sendChatMessage ( senderId, receiverId, message ) {
    let messageObject =  (message instanceof FormData) ? message : {
        senderId : senderId,
        receiverId : receiverId,
        message : message
    }

    let headers = !(message instanceof FormData) ? {} : {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
    }

    return new Promise ( (resolve, reject) => {
        let response =  axios.post(`${host}:${port}/sendMessage`, messageObject, headers);

        response.then (function (res) {
            resolve(res);
        })
        .catch(err=>{
            reject(err);
        })

    });
}

export function deleteMessage ( chatId ) {
    return new Promise ( (resolve, reject) => {
        let response =  axios.delete(`${host}:${port}/deleteMessage/${chatId}`);
        response.then (function (res) {
            resolve(res.chat);
        })
        .catch(err=>{
            console.log(err);
        })

    });
}

export function updateMessage ( chatId, message ) {
    return new Promise ( (resolve, reject) => {
        let response =  axios.post(`${host}:${port}/updateMessage`, {
            chatId : chatId,
            message : message
        });

        response.then (function (res) {
            resolve(res);
        })
        .catch(err=>{
            reject(err);
        })

    });
}