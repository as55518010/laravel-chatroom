<template>
  <div class="login-modal-container animated fadeIn" @click="outsideClick($event)">
    <transition name="custom-classes-transition" enter-active-class="animated fadeInDown"
      leave-active-class="animated fadeOutUp">
      <span v-if="backendError" class="backend-error">{{backendError}}</span>
    </transition>
    <div class="login-modal-content animated fadeInUp">
      <div class="modal-indicator"><span>登入</span></div>
      <div class="form-element">
        <label for="email">信箱:</label>
        <input name="email" v-validate="'required|email'" type="email" class="cool-input" v-model="email" autofocus>
        <span class="error-text animated fadeInDown" v-show="errors.has('email')">{{ errors.first('email') }}</span>
      </div>
      <div class="form-element">
        <label for="password">密碼:</label>
        <input name="password" v-validate="'required|min:6'" type="password" class="cool-input" v-model="password">
        <span class="error-text animated fadeInDown"
          v-show="errors.has('password')">{{ errors.first('password') }}</span>
      </div>
      <div class="form-element">
        <button class="cool-btn" @click="submit">
          <span v-show="!loading">登入</span>
          <img v-show="loading" src="/img/preloader.svg" alt="">
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "loginModal",
  data() {
    return {
      email: "",
      password: "",
      backendError: "",
      loading: false,
    };
  },
  methods: {
    ...mapActions("user", ["login"]),
    outsideClick(e) {
      if (e.target == document.querySelector(".login-modal-container")) {
        this.$EventBus.$emit("changeLoginModal", false);
      }
    },
    async submit() {
      const status = await this.$validator.validate();
      if (status) {
        this.loading = true;
        try {
          await this.login({
            email: this.email,
            password: this.password,
          });
          this.loading = false;
          this.$router.push({ name: "chat" });
        } catch (error) {
          this.loading = false;
          if (error.response.status == 401 || error.response.status == 422)
            this.backendError = "電子郵件或密碼不正確，請重試。";
          else this.backendError = error.response.data.error;
          this.resetError();
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
.login-modal-container {
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

.login-modal-content {
  width: 400px;
  margin: auto;
  box-shadow: 0 10px 43px 5px rgba(0, 0, 0, 0.2);
  border-radius: 20px;
  background: #fff;
  background: #f5f6fa;
  justify-content: space-around;
  position: relative;
  display: flex;
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

.additional {
  padding-bottom: 10px;
  height: 25px;
  font-size: 15px;
  text-align: right;

  a {
    color: #0097e6;
  }
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
</style>
