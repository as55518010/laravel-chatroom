import request from "@/utils/Request";

/**
 * 會員登入
 * @param {*} data
 */
export function loginApi(data) {
    return request({
        url: "/user/login",
        method: "post",
        data,
    });
}
/**
 * 會員註冊
 * @param {*} data
 */
export function registerApi(data) {
    return request({
        url: "/user/register",
        method: "post",
        data,
    });
}
/**
 * 會員信息
 * @param {*} data
 */
export function infoApi() {
    return request({
        url: "/user/info",
        method: "get",
    });
}
/**
 * 搜尋會員(like name)
 * @param {*} data
 */
export function userFindApi(params) {
    return request({
        url: "/user/find",
        method: "get",
        params,
    });
}
/**
 * 獲取會員聊天室
 * @param {*} data
 */
export function userFindChatroomApi(params) {
    return request({
        url: "/user/find/chatroom",
        method: "get",
        params,
    });
}
/**
 * 會員登出
 * @param {*} data
 */
export function logoutApi() {
    return request({
        url: "/user/logout",
        method: "post",
    });
}

/**
 * 會員修改
 * @param {*} data
 */
export function putPassApi(data) {
    return request({
        url: "/user/password",
        method: "put",
        data,
    });
}
