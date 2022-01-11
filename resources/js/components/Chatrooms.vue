<template>
  <div class="chatrooms">
    <div class="chatrooms-wrapper" v-if="(!searching && chatrooms.length!==0)">
      <div :class="['chatroom', {'active-chatroom' : (activeChatroom==chatroom.chatroom_id)}]"
        v-for="chatroom in chatrooms"
        @click="enterChatroom({chatroomId:chatroom.id,contactUser:chatroom.chatroom_user})" :key="chatroom.id">
        <div>
          <img :src="chatroom.chatroom_user[0].users.avatar" alt="">
        </div>
        <div>
          <h3>{{chatroom.chatroom_user[0].users.name}}</h3>
          <p>
            <strong
              v-if="chatroom.last_message.user_id!=userId">{{chatroom.chatroom_user[0].users.name.split(" ")[0]}}:</strong>
            <strong v-else>你 :</strong>
            {{chatroom.last_message.body}}
          </p>
        </div>
      </div>
    </div>
    <div class="chatrooms-placeholder" v-if="(chatrooms.length===0 && !searching)">
      <img src="/img/friends.svg" alt="">
      <h3>現在尋找朋友並開始聊天咯!</h3>
    </div>
    <div class="chatrooms_wrapper search-results" v-if="searching">
      <div v-for="user in result" class="chatroom" :key="user.id" @click="getFoundChatroom(user)">
        <div>
          <img :src="user.avatar" alt="">
        </div>
        <div>
          <h3>{{user.name}}</h3>
        </div>
      </div>
    </div>
    <div class="chatroom-search">
      <input class="cool-input" type="text" placeholder="尋找朋友..." v-model="keyword" @keyup='search'>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";

import { userFindApi, userFindChatroomApi } from "@/api/user";

export default {
  name: "chatrooms",
  computed: {
    ...mapState("user", ["userId"]),
    ...mapState("chatroom", [
      "chatrooms",
      "currentChatroom",
      "currentContact",
      "activeChatroom",
    ]),
  },
  data() {
    return {
      searching: false,
      result: "",
      keyword: "",
    };
  },
  async created() {
    this.getChatrooms();
    Echo.private("chatrooms." + this.userId).listen("ChatroomEvent", (e) => {
      this.getChatrooms();
    });
  },
  methods: {
    ...mapActions("chatroom", ["getChatrooms", "getChatroom", "enterChatroom"]),
    ...mapMutations("chatroom", [
      "SET_CHOSEN",
      "SET_LOADING",
      "SET_TYPING_INDICATOR",
      "SET_CURRENT_CONTACT",
      "SET_CURRENT_CHATROOM",
    ]),
    async search() {
      if (this.keyword === "") return (this.searching = false);

      this.searching = true;
      try {
        const { data } = await userFindApi({ keyword: this.keyword });
        this.result = data;
      } catch (error) {
        this.searching = false;
        console.log(error);
      }
    },
    async getFoundChatroom(user) {
      this.searching = false;
      this.keyword = "";
      try {
        const { data } = await userFindChatroomApi({
          id: user.id,
        });
        let currentChatroom = {
          messages: [],
        };
        if (data) {
          currentChatroom = data;
        }
        this.SET_CURRENT_CONTACT([{ user_id: user.id, users: user }]);
        this.SET_CURRENT_CHATROOM(currentChatroom);
        this.SET_CHOSEN(true);
      } catch (error) {
        console.log({ error });
      }
    },
  },
};
</script>


<style lang="scss" scoped>
.chatrooms {
  justify-content: space-between;
  position: relative;
  height: 100%;
  display: flex;
  flex-direction: column;
  background: #f5f6fa;
  width: 300px;
  align-items: center;
  h3 {
    text-transform: capitalize;
  }
  .chatrooms-wrapper {
    display: flex;
    flex-direction: column;
    width: 100%;
  }
  .chatroom {
    display: flex;
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
    transition: all 0.2s;
    align-items: center;
    cursor: pointer;
    color: #57606f;
    position: relative;
    &:hover {
      background: #dfe4ea;
    }

    &:after {
      content: "";
      position: absolute;
      top: 0;
      bottom: 0;
      background: transparent;
      left: 10px;
      right: 10px;
      border-bottom: 1px solid #e6e8ea;
    }

    h3 {
      font-weight: normal;
      margin: 0 10px;
    }

    div:nth-of-type(1) {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      position: relative;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      border-radius: 50%;
      display: flex;
      width: 50px;
      height: 50px;

      img {
        height: 110%;
        width: auto;
      }
    }

    div:nth-of-type(2) {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 0 10px;
      width: 60%;

      p {
        margin: 0;
        width: 100%;
        margin: 0;
        white-space: nowrap;
        text-overflow: ellipsis;
        width: 100%;
        overflow: hidden;
      }

      h3 {
        width: 100%;
      }
    }
  }

  .active-chatroom {
    background: #dfe4ea !important;
    border-left: 5px solid #70a1ff;
  }
  .chatroom-search {
    border-top: 1px solid #e6e8ea;
    padding: 5px;
    box-sizing: border-box;
    width: 100%;

    .cool-input {
      outline: none;
    }
  }
  .chatrooms-placeholder {
    justify-content: center;
    display: flex;
    align-items: center;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    text-align: center;
    height: 100%;
    color: #57606f;

    h3 {
      text-transform: none;
    }

    img {
      width: 80px;
    }
  }
  .chatrooms_wrapper {
    display: flex;
    flex-direction: column;
    width: 100%;
  }
}
</style>
