import request from "@/utils/Request";

/**
 * 發送訊息
 * @param {*} data
 */
 export function sendMessageApi(data) {
    return request({
        url: "/message",
        method: "post",
        data,
    });
}
