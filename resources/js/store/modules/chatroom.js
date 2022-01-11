import { chatroomsApi, chatroomApi } from "@/api/chatroom";

const state = {
    activeChatroom: "",
    typingIndicator: "",
    chatrooms: "",
    loading: false,
    chosen: false,
    currentContact: [],
    currentChatroom: {
        messages: [],
    },
};
const getters = {};
const mutations = {
    SET_ACTIVE_CHATROOM: (state, data) => {
        state.activeChatroom = data;
    },
    SET_CHATROOMS: (state, data) => {
        state.chatrooms = data;
    },
    SET_CURRENT_CHATROOM: (state, data) => {
        state.currentChatroom = data;
    },
    SET_CURRENT_CONTACT: (state, contactUser) => {
        state.currentContact = contactUser;
    },
    SET_CHOSEN: (state, chosen) => {
        state.chosen = chosen;
    },
    SET_LOADING: (state, loading) => {
        state.loading = loading;
    },
    SET_TYPING_INDICATOR: (state, typingIndicator) => {
        state.typingIndicator = typingIndicator;
    },
};
const actions = {
    async getChatrooms({ commit }) {
        const { data } = await chatroomsApi();
        commit("SET_CHATROOMS", data);
    },
    async getChatroom({ commit }, { chatroomId, contactUser }) {
        const { data } = await chatroomApi(chatroomId);
        commit("SET_CURRENT_CHATROOM", data);
        commit("SET_CURRENT_CONTACT", contactUser);
    },
    async enterChatroom(
        { commit, dispatch, state },
        { chatroomId, contactUser }
    ) {
        console.log( chatroomId, contactUser);
        commit("SET_ACTIVE_CHATROOM", chatroomId);
        commit("SET_LOADING", true);
        Echo.leave("chat." + state.currentChatroom.id);
        try {
            await dispatch("getChatroom", { chatroomId, contactUser });
            commit("SET_CHOSEN", true);
            commit("SET_LOADING", false);
            Echo.join("chat." + state.currentChatroom.id)
                .listen("MessageSent", (e) => {
                    if (e.message.chatroom_id == state.currentChatroom.id) {
                        commit("SET_TYPING_INDICATOR", {
                            typing: false,
                        });
                        state.currentChatroom.messages.push(e.message);
                    }
                })
                .listenForWhisper("typing", (e) => {
                    console.log(e);
                    commit("SET_TYPING_INDICATOR", e.typingEvent);
                });
        } catch (error) {
            console.log({ error });
        }
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};
