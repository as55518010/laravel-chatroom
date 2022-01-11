<template>
  <div class="register-modal-container animated fadeIn" @click="outsideClick($event)">
    <transition name="custom-classes-transition" enter-active-class="animated fadeInDown"
      leave-active-class="animated fadeOutUp">
      <span v-if="backendError" class="backend-error">{{backendError}}</span>
    </transition>
    <transition name="custom-classes-transition" enter-active-class="animated fadeInDown"
      leave-active-class="animated fadeOutUp">
      <span v-if="backendOkay" class="backend-okay">你現在已經註冊了，請前往首頁重新登入</span>
    </transition>
    <div class="register-modal-content animated fadeInUp">
      <div class="modal-indicator"><span>註冊</span></div>
      <div class="form-element">
        <label for="name">暱稱:</label>
        <input v-validate="'required|max:255'" name="name" class="cool-input" type="text" v-model="name" autofocus>
        <span class="error-text animated fadeInDown" v-show="errors.has('name')">{{ errors.first('name') }}</span>
      </div>
      <div class="form-element">
        <label for="email">信箱:</label>
        <input v-validate="'required|email|max:255|'" name="email" class="cool-input" type="email" v-model="email">
        <span class="error-text animated fadeInDown" v-show="errors.has('email')">{{ errors.first('email') }}</span>
      </div>
      <div class="form-element">
        <label for="password">密碼:</label>
        <input v-validate="'required|min:6'" name="password" ref="password" class="cool-input" type="password"
          v-model="password">
        <span class="error-text animated fadeInDown"
          v-show="errors.has('password')">{{ errors.first('password') }}</span>
      </div>
      <div class="form-element">
        <label for="password">重複輸入密碼:</label>
        <input v-validate="'required|confirmed:password'" name="password repeat" class="cool-input" type="password"
          v-model="password_confirmation">
        <span class="error-text animated fadeInDown"
          v-show="errors.has('password repeat')">{{ errors.first('password repeat') }}</span>
      </div>
      <div class="form-element">
        <button class="cool-btn" @click="registerUser">
          <span v-show="!loading">註冊</span>
          <img v-show="loading" src="/img/preloader.svg" alt="">
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { registerApi } from "@/api/user";

export default {
  name: "registerModal",
  data() {
    return {
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      backendError: "",
      backendOkay: false,
      loading: false,
    };
  },
  methods: {
    outsideClick(e) {
      if (e.target == document.querySelector(".register-modal-container")) {
        this.$EventBus.$emit("changeLoginModal", false);
      }
    },
    async registerUser() {
      let result = await this.$validator.validate();
      if (result) {
        this.loading = true;

        try {
          await registerApi({
            name: this.name,
            email: this.email,
            password: this.password,
            password_confirmation: this.password_confirmation,
          });
          this.loading = false;
          this.backendOkay = true;
        } catch (error) {
          this.loading = false;
          this.resetError();
          if (error.response.status == 422) {
            this.backendError = "電子郵件已存在";
          } else {
            this.backendError = "無效數據";
          }
        }
      }
    },
    resetError() {
      setTimeout(() => {
        this.backendError = "";
      }, 4000);
    },
  },
};
</script>
<style lang="scss" scoped>
.register-modal-container {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  z-index: 8;
}

.register-modal-content {
  width: 400px;
  margin: auto;
  box-shadow: 0 10px 43px 5px rgba(0, 0, 0, 0.2);
  border-radius: 20px;
  background: #fff;
  background: #f5f6fa;
  display: flex;
  justify-content: space-around;
  position: relative;
  flex-direction: column;
  align-items: center;
}

.error-text {
  font-size: 13px;
  position: absolute;
  color: #f57e7d;
  bottom: -10px;
  right: 30px;
}

.backend-error {
  background: #ff4757a1;
  color: #fff;
  position: absolute;
  top: 0;
  padding: 10px;
  text-align: center;
  width: 100%;
}

.backend-okay {
  background: #1eb938a1;
  color: #fff;
  position: absolute;
  top: 0;
  padding: 10px;
  text-align: center;
  width: 100%;
}
</style>
