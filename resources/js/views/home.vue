<template>
  <div class="container">
    <div class="art"></div>
    <LoginModal v-if="loginModal"></LoginModal>
    <RegisterModal v-if="registerModal"></RegisterModal>
    <div class="header">
      <div class="logo-container">
        <img src="/img/logo.png" alt="">
      </div>
    </div>
    <div class="hero">
      <h1>歡迎來到聊天室</h1>
      <div class="btn-container">
        <button class="cool-btn" @click="loginModal = true">登入</button>
        <button class="cool-btn" @click="registerModal = true">註冊</button>
      </div>
    </div>
  </div>
</template>

<script>
import LoginModal from "@/components/LoginModal";
import RegisterModal from "@/components/RegisterModal";

export default {
  name: "home",
  data() {
    return {
      loginModal: false,
      registerModal: false,
    };
  },
  components: {
    LoginModal,
    RegisterModal,
  },
  created() {
    // 註冊事件
    this.eventBus();
  },
  methods: {
    eventBus() {
      this.$EventBus.$on("changeLoginModal", (status) => {
        this.loginModal = status;
      });
      this.$EventBus.$on("changeLoginModal", (status) => {
        this.registerModal = status;
      });
    },
  },
};
</script>


<style lang="scss" scoped>
.logo-container {
  img {
    width: 130px;
  }
}

.header {
  width: 100%;
  display: flex;
  justify-content: space-between;
  margin-bottom: 150px;
}

.nav {
  width: 400px;
  font-size: 15px;
  position: relative;
  z-index: 1;
  text-transform: uppercase;
  font-family: "karla", sans-serif;

  a {
    text-decoration: none;
    color: #767b85;
  }
  ul {
    display: flex;
    justify-content: space-between;
  }

  li {
    list-style: none;
    font-weight: bold;
    font-size: 15px;
    letter-spacing: 1px;
  }
}

.hero {
  color: #0c1326;
  font-family: "montserrat", sans-serif;
  position: relative;
  z-index: 1;

  h1 {
    font-size: 50px;
    display: flex;
    align-items: center;

    img {
      width: 230px;
      margin: 0 10px;
    }
  }

  h5 {
    color: #6d727d;
    font-size: 17px;
    font-family: "karla";
    width: 530px;
    line-height: 40px;

    span {
      font-size: 20px;
    }
  }

  .btn-container {
    width: 330px;
    display: flex;
    justify-content: space-between;
  }

  button {
    &:nth-of-type(2) {
      background: #0097e6;
    }
  }
}

.art {
  background: #fff;
  width: 700px;
  height: 700px;
  top: -300px;
  border-radius: 50%;
  position: absolute;
  right: -229px;
}

.active {
  color: #0c1326 !important;
}
</style>
