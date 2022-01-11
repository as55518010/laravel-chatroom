<template>
  <div>
    <transition name="custom-classes-transition" enter-active-class="animated fadeInDown"
      leave-active-class="animated fadeOutUp">
      <span v-if="backendError" class="backend-error">{{backendError}}</span>
    </transition>
    <transition name="custom-classes-transition" enter-active-class="animated fadeInDown"
      leave-active-class="animated fadeOutUp">
      <span v-if="backendOkay" class="backend-okay">您的密碼已成功更改。</span>
    </transition>
    <div class="sidebar">
      <div class="main-menu">
        <div class="avatar-container">
          <el-avatar :size="200" :src="userAvatar"></el-avatar>
        </div>
        <h3>{{userName}}</h3>
        <ul>
          <li @click="settings=true">
            <p>設置</p>
          </li>
          <li @click="userLogout">
            <p>登出</p>
          </li>
        </ul>
      </div>
      <transition name="custom-classes-transition" enter-active-class="animated fadeInLeft"
        leave-active-class="animated fadeOutLeft">
        <div class="settings" v-if="settings">
          <el-button type="info" @click="settings=false">回上頁</el-button>

          <h1 @click="settings=false" class="arrow-back"><i class="fas fa-arrow-left"></i></h1>
          <h5>改變頭像</h5>
          <!-- <input type="file" id="avatar-input" @change="changeAvatarInit"> -->
          <el-upload class="avatar-container" name="avatar" :on-success="handleAvatarSuccess"
            :before-upload="beforeAvatarUpload" :headers="{'Authorization': `Bearer ${token}`}"
            action="/api/user/avatar" :show-file-list="false">
            <img v-if="userAvatar" :src="userAvatar" class="avatar">
            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
          </el-upload>
          <h5>更改密碼</h5>
          <div class="form-element">
            <input @keyup.enter='validate' class="cool-input" v-validate="'required'" type="password" name="password"
              placeholder="現在密碼" v-model="password">
            <span class="error-text animated fadeInDown"
              v-show="errors.has('password')">{{ errors.first('password') }}</span>
          </div>
          <div class="form-element">
            <input @keyup.enter='validate' class="cool-input" v-validate="'required|min:6'" type="password"
              name="newPassword" placeholder="新密碼" v-model="newPassword">
            <span class="error-text animated fadeInDown"
              v-show="errors.has('newPassword')">{{ errors.first('newPassword') }}</span>
          </div>
          <el-button type="primary" plain @click="validate" :disabled="(!password || !newPassword)">保存密碼</el-button>
        </div>
      </transition>
    </div>
  </div>
</template>


<script>
import { mapState, mapActions, mapMutations } from "vuex";
import { putPassApi } from "@/api/user";

export default {
  name: "sidebar",
  computed: {
    ...mapState("user", ["userId", "userAvatar", "userName", "token"]),
  },
  data() {
    return {
      settings: false,
      password: "",
      newPassword: "",
      backendOkay: false,
      backendError: "",
    };
  },
  methods: {
    ...mapActions("user", ["logout"]),
    ...mapMutations("user", ["SET_USER_AVATAR"]),
    handleAvatarSuccess(res) {
      this.SET_USER_AVATAR(res.new_path);
    },
    beforeAvatarUpload(file) {
      const isJPG = file.type === "image/jpeg";
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error("上傳頭像圖片只能是 JPG 格式!");
      }
      if (!isLt2M) {
        this.$message.error("上傳頭像圖片大小不能超過 2MB!");
      }
      return isJPG && isLt2M;
    },
    userLogout() {
      this.logout();
      this.$router.push("/");
    },
    browseNewAvatar() {
      $("#avatar-input").click();
    },
    async changePassword() {
      try {
        await putPassApi({
          password: this.password,
          new_password: this.newPassword,
        });
        this.backendOkay = true;
        this.resetOkay();
      } catch (error) {
        this.loading = false;
        this.resetError();
        this.backendError = error.response.data.message;
      }
    },

    resetError() {
      setTimeout(() => {
        this.backendError = "";
      }, 4000);
    },

    resetOkay() {
      setTimeout(() => {
        this.backendOkay = false;
      }, 4000);
    },

    changeAvatarInit(e) {
      var fileReader = new FileReader();

      fileReader.readAsDataURL(e.target.files[0]);
      fileReader.onload = (e) => {
        this.userAvatar = e.target.result;
        $("#save-avatar").removeAttr("disabled");
      };
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
.backend-error,
.backend-okay {
  background: #ff4757a1;
  color: #fff;
  position: absolute;
  top: 0;
  padding: 10px;
  z-index: 20;
  text-align: center;
  left: 0;
  right: 0;
}

.backend-okay {
  background: #1eb938a1 !important;
}
.sidebar {
  position: relative;
  height: 100%;
  display: flex;
  flex-direction: column;
  background: #f5f6fa;
  width: 300px;
  align-items: center;
  justify-content: center;

  .main-menu {
    display: flex;
    width: 100%;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    flex: 1;
  }

  #avatar-input {
    opacity: 0;
    position: absolute;
    left: -500px;
  }

  .settings {
    position: absolute;
    justify-content: space-around;
    top: 0;
    left: 0;
    z-index: 3;
    background: #f5f6fa;
    align-items: center;
    right: 0;
    bottom: 0;
    padding: 10px 20px;
    display: flex;
    flex-direction: column;

    .error-text {
      font-size: 13px;
      position: absolute;
      color: #f57e7d;
      bottom: 0px;
      left: 35px;
    }

    .cool-btn {
      width: 125px;
      height: 30px;
      font-size: 12px;
      &:disabled {
        box-shadow: none;
        background: #c3c3c3;
        cursor: default;
        transition: all 0.2s;
      }
    }
    .avatar-container {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      position: relative;

      .avatar {
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 50%;
        display: flex;
        width: 100%;
        height: 100%;
      }

      img {
        height: 110%;
        width: auto;
      }
      cursor: pointer;
      overflow: hidden;

      span {
        position: absolute;
        top: 0;
        left: 0;
        justify-content: center;
        display: flex;
        right: 0;
        bottom: 0;
        background: #000;
        color: #fff;
        opacity: 0;
        transition: all 0.2s;
        font-size: 40px;
        align-items: center;
        z-index: 4;
      }

      &:hover span {
        opacity: 0.8;
      }
    }

    h1,
    h5 {
      margin: 0;
    }

    h5 {
      border-top: 1px solid #d0d0d0;
      padding-top: 20px;
      width: 100%;
      text-align: center;
    }

    .arrow-back {
      cursor: pointer;
      color: #70a1ff;
    }

    h5 {
      font-size: 20px;
      text-transform: uppercase;
      color: #57606f;
    }
  }

  h3 {
    text-transform: capitalize;
  }

  ul {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    color: #57606f;
    flex-direction: column;
    padding: 0;
  }

  li {
    list-style: none;
    justify-content: center;
    padding: 20px 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    width: 100%;
    text-transform: uppercase;
    transition: all 0.2s;
    box-sizing: border-box;
    position: relative;

    &:after {
      content: "";
      position: absolute;
      top: 0;
      bottom: 0;
      background: transparent;
      left: 30px;
      right: 30px;
      border-bottom: 1px solid #e6e8ea;
    }

    &:nth-of-type(1):before {
      content: "";
      position: absolute;
      top: 0;
      bottom: 0;
      background: transparent;
      left: 30px;
      right: 30px;
      border-top: 1px solid #e6e8ea;
    }

    &:hover {
      background: #dfe4ea;
      border-right: 5px solid #70a1ff;
    }

    p {
      position: relative;
      margin: 0;
      padding: 0;
      span {
        height: 15px;
        width: auto;
        padding: 0 5px;
        font-size: 12px;
        background: #da7079;
        position: absolute;
        color: #fff;
        top: -12px;
        right: -11px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
      }
    }

    &:nth-of-type(3):hover {
      border-right: 5px solid #ff4757;
    }
  }
}
</style>
