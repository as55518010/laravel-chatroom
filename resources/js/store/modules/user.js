import { loginApi, infoApi } from "@/api/user";
import { isEmpty } from "lodash-es";
import Vue from "vue";

const state = {
    token: "",
    userId: "",
    userAvatar: "",
    userName: "",
    userEmail: "",
};
const getters = {
    accessToken: (state) => {
        return (state.token = isEmpty(state.token)
            ? window.localStorage.getItem("token")
            : state.token);
    },
    userInfo: (state) => {
        return {
            userId: state.userId,
            userAvatar: state.userAvatar,
            userName: state.userName,
            userEmail: state.userEmail,
        };
    },
};
const mutations = {
    SET_TOKEN: (state, token) => {
        state.token = token;
        localStorage.setItem("token", token);
    },
    SET_INFO: (state, user) => {
        state.userId = user.id;
        state.userAvatar = user.avatar;
        state.userName = user.name;
        state.userEmail = user.email;
    },
    SET_USER_AVATAR: (state, avatar) => {
        state.userAvatar = avatar;
    },
};
const actions = {
    async login({ commit, dispatch }, form) {
        const token = await loginApi(form);
        commit("SET_TOKEN", token);
        dispatch("userInfo");
    },
    async userInfo({ commit, state }) {
        const user = await infoApi();
        commit("SET_INFO", user);
        Echo.connector.pusher.config.auth.headers["Authorization"] =
            "Bearer " + state.token;
    },
    async logout({ commit }) {
        try {
            await logoutApi();
            commit("SET_TOKEN", "");
        } catch (error) {
            commit("SET_TOKEN", "");
        }
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};
