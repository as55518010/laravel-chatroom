import Vue from "vue";
import VueRouter from "vue-router";
import store from "@/store/index";
import { isEmpty, every } from "lodash-es";
import { Message } from "element-ui";

Vue.use(VueRouter);

import Home from "@/views/home";
import Chat from "@/views/chat";

const router = new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "home",
            component: Home,
        },
        {
            path: "/chat",
            name: "chat",
            component: Chat,
            meta: { requiresAuth: true },
        },
    ],
});

router.beforeEach(async (to, from, next) => {
    let userInfo = store.getters["user/userInfo"];
    if (to.meta.requiresAuth) {
        if (every(userInfo, (val) => isEmpty(val))) {
            try {
                await store.dispatch("user/userInfo");
                next();
            } catch (error) {
                Message.error("Token已經超時，請重新登入");
                store.dispatch("user/logout");
                next({ name: "home" });
            }
        } else {
            next();
        }
    } else if (every(userInfo, (val) => !isEmpty(val))) {
        next({ name: "chat" });
    } else next();
});

export default router;
