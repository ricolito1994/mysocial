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
    return new Promise ( (resolve, reject) => {
        let response =  axios.post(`${host}:${port}/sendMessage`, {
            senderId : senderId,
            receiverId : receiverId,
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