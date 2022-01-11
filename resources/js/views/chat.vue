<template>
  <div class="chat-container">
    <Sidebar />
    <Chatting v-if="chosen" />
    <div class="chat-placeholder" v-if="!chosen">
      <img src="/img/paper-plane.svg" alt="">
    </div>
    <Chatrooms />
  </div>
</template>



<script>
import { mapState } from "vuex";

import Sidebar from "@/components/Sidebar";
import Chatrooms from "@/components/Chatrooms";
import Chatting from "@/components/Chatting";

export default {
  name: "chat",
  components: {
    Sidebar,
    Chatting,
    Chatrooms,
  },
  computed: {
    ...mapState("user", ["userId", "userAvatar", "userName", "userEmail"]),
    ...mapState("chatroom", ["chosen", "currentContact"]),
  },
  data() {
    return {
      tempUserAvatar: "",
      message: "",
      password: "",
      newPassword: "",
      typingTimer: "",
      backendOkay: false,
    };
  },
  methods: {
    browseNewAvatar() {
      $("#avatar-input").click();
    },
    validate() {
      this.$validator.validate().then((result) => {
        if (result) {
          this.changePassword();
        }
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.chat-container {
  background: #fff;
  height: 100vh;
  display: flex;
  position: relative;
  width: 100%;
  .chat-placeholder {
    flex: 1;
    display: flex;
    position: relative;
    align-items: center;
    width: auto;
    height: 100%;
    padding: 0 10px;
    flex-direction: column;
    justify-content: center;
    color: #57606f;
    img {
      height: auto;
      width: 100px;
    }
  }
}
</style>

