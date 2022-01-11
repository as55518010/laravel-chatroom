<template>
  <div class="chat">
    <div class="chat-header">
      <h3>{{currentContact[0].users.name}}</h3>
    </div>
    <div class="chat-content" v-if="currentChatroom">
      <div class="message other-message" v-if="typingIndicator.typing">
        <div class="message-wrapper">
          <div class="chat-avatar">
            <img :src="currentContact[0].users.avatar" alt="">
          </div>
          <p>
            <TypingAnimation />
          </p>
        </div>
      </div>
      <div v-if="!loading">
        <div v-if="currentChatroom.messages.length > 0">
          <div :class="[(message.user_id == userId)? 'own-message' : 'other-message', 'message']"
            v-for="message in currentChatroom.messages" :key="message.id">
            <div class="message-wrapper">
              <div class="chat-avatar">
                <img :src="currentContact[0].users.avatar" alt="" v-if="message.user_id != userId">
              </div>
              <p>{{message.body}}</p>
            </div>
          </div>
        </div>
        <el-empty v-else description="暫無聊天紀錄"></el-empty>
      </div>
      <img v-show="loading" id="chat-preloader" src="/img/bpreloader.svg" alt="">
    </div>
    <div class="text-field">
      <textarea placeholder="在這裡輸入您的消息..." v-model="message" @keydown.enter.prevent="sendMessage" autofocus
        @keydown="isTyping(true)"></textarea>
      <button class="cool-btn" @click="sendMessage">發送</button>
    </div>
  </div>
</template>


<script>
import { mapState, mapActions } from "vuex";

import { sendMessageApi } from "@/api/message";
import TypingAnimation from "@/components/typingAnimation";

export default {
  name: "chatting",
  computed: {
    ...mapState("user", ["userId", "userName"]),
    ...mapState("chatroom", [
      "currentContact",
      "currentChatroom",
      "loading",
      "typingIndicator",
    ]),
  },
  components: {
    TypingAnimation,
  },
  data() {
    return {
      message: "",
    };
  },
  methods: {
    ...mapActions("chatroom", ["getChatrooms"]),
    async sendMessage() {
      if (this.message == "" || !this.message.replace(/\s/g, "").length) return;
      let messageToSend = this.message;
      this.message = "";
      try {
        const response = await sendMessageApi({
          chatroom_id: this.currentChatroom.id ?? null,
          body: messageToSend,
          message_to: this.currentContact[0].user_id,
        });
        if (response.new_chatroom) {
            this.getChatrooms()
        }
      } catch (error) {
        console.log(error);
      }
    },
    isTyping(status) {
      if (this.currentChatroom.id) {
        clearTimeout(this.typingTimer);
        this.typingTimer = setTimeout(() => {
          Echo.join("chat." + this.currentChatroom.id).whisper("typing", {
            typingEvent: {
              name: this.userName.split(" ")[0],
              typing: false,
            },
          });
        }, 2000);
        Echo.join("chat." + this.currentChatroom.id).whisper("typing", {
          typingEvent: {
            name: this.userName.split(" ")[0],
            typing: status,
          },
        });
      }
    },
  },
};
</script>


<style lang="scss" scoped>
.chat {
  flex: 1;
  display: flex;
  position: relative;
  align-items: center;
  width: auto;
  height: 100%;
  padding: 0 10px;
  flex-direction: column;
  justify-content: space-between;
  .chat-content {
    position: relative;
    height: 100%;
    width: 100%;
    padding-bottom: 20px;
    overflow-y: auto;
    display: flex;
    flex-direction: column-reverse;

    /* width */
    &::-webkit-scrollbar {
      width: 10px;
    }

    /* Track */
    &::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    /* Handle */
    &::-webkit-scrollbar-thumb {
      background: #ced6e0;
      border-radius: 20px;
    }

    /* Handle on hover */
    &::-webkit-scrollbar-thumb:hover {
      background: #aeb5bd;
    }
  }
  .own-message {
    justify-content: flex-end;
    p {
      background: #1e90ff;
    }
    .message-wrapper {
      justify-content: flex-end;
    }
  }

  .other-message {
    p {
      background: #747d8c;
    }
  }

  .message {
    width: 100%;
    display: flex;
    align-items: center;
    flex-shrink: 0;
    p {
      border-radius: 20px;
      padding: 7px 10px;
      word-break: break-word;
      color: #fff;
      display: inline-block;
      margin: 5px;
    }

    .message-wrapper {
      width: 50%;
      display: flex;
      align-items: center;
    }
  }

  .chat-avatar {
    height: 30px;
    margin-right: 5px;
    width: 30px;
    border-radius: 50%;
    flex-shrink: 0;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;

    img {
      height: 110%;
      width: auto;
    }
  }

  .text-field {
    display: flex;
    width: 100%;
    align-items: center;
    min-height: 100px;
    border-top: 1px solid #e6e8ea;

    .cool-btn {
      width: 100px !important;
      font-size: 15px;
      margin: 0 5px;
    }

    textarea {
      font-family: karla;
      border: none;
      border: 1px solid #e6e8ea;
      font-size: 16px;
      border-radius: 20px;
      min-height: 1em;
      padding: 10px;
      outline: none;
      width: auto;
      resize: none;
      flex: 1;
    }
  }

  .chat-header {
    border-bottom: 1px solid #e6e8ea;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-shrink: 0;
    width: 100%;
    height: 80px;

    h3 {
      text-transform: capitalize;
    }
  }
  #chat-preloader {
    position: absolute;
    top: 0;
    bottom: 0;
    margin: auto;
    left: 0;
    right: 0;
  }
}
</style>
