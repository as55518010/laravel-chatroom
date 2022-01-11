import axios from "axios";
import { Message } from "element-ui";
import store from "@/store/index";

// create an axios instance
const service = axios.create({
    baseURL: process.env.MIX_APP_BASE_API, // url = base url + request url
    withCredentials: true, // send cookies when cross-domain requests
    // timeout: 20000 // request timeout
});

// request interceptor
service.interceptors.request.use(
    (config) => {
        if (store.getters["user/accessToken"]) {
            config.headers.Authorization = `Bearer ${store.getters["user/accessToken"]}`;
        }
        return config;
    },
    (error) => {
        // do something with request error
        console.log(error); // for debug
        return Promise.reject(error);
    }
);

// response interceptor
service.interceptors.response.use(
    (response) => {
        const res = response.data;
        return res;
    },
    (error) => {
        if (error.response.status !== 401) {
            Message({
                message: error.response.data.message ?? error.message,
                type: "error",
            });
        }
        return Promise.reject(error);
    }
);

export default service;
