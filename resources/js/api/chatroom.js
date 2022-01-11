import request from "@/utils/Request";

/**
 * 獲取會員所有聊天室
 * @param {*} data
 */
export function chatroomsApi() {
    return request({
        url: "/chatroom",
        method: "get",
    });
}
/**
 * 獲取會員聊天
 * @param {*} data
 */
export function chatroomApi(chatroom_id) {
    return request({
        url: `/chatroom/${chatroom_id}`,
        method: "get",
    });
}
