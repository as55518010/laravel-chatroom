require("./bootstrap");
import App from "@/layouts/default";
import router from "./router";
import Vue from "vue";
import store from '@/store/index'

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

import VeeValidate from "vee-validate";
// support lang
import tw from "vee-validate/dist/locale/zh_TW";

const config = {
    events: 'input|blur', //這是為了讓使用者離開該欄位時觸發驗證
    // 初始語系
    locale: "tw",
    // 預設各語系檢核失敗提示的文字 (需要對應 locale 設定)
    dictionary: { tw },
};

Vue.use(VeeValidate,config);
Vue.use(ElementUI);


/* 全局事件總線 */
Vue.prototype.$EventBus = new Vue()

new Vue({
    el: "#app",
    router,
    store,
    components: { App },
});
