import {boot} from "quasar/wrappers";
import axios from "axios";
import qs from "qs";
import {LocalStorage, Notify} from "quasar";
import _ from "lodash";

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)
const token = LocalStorage.getItem("access_token");
// console.log(token)
let headers = {
  "X-Requested-With": "XMLHttpRequest",
  "Content-Type": "application/json",
}

if (process.env.X_TENANT) {
  headers['X-Tenant'] = process.env.X_TENANT
}

const axiosInstance = axios.create({
  paramsSerializer: (params) => {
    return qs.stringify(params, {arrayFormat: "repeat"});
  },
  headers: headers,
  baseURL: process.env.API_URL
});

export default boot(({app, store}) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api

  axiosInstance.interceptors.response.use(
    function (response) {
      // Do something with response data
      return response;
    },
    function (error) {
      // Do something with response error
      // const $store = useStore()
      if (error.response && error.response.status === 404) {
        Notify.create({
          message: "Resource not found",
          timeout: 3000,
          color: "negative",
        });
      } else if (error.response && error.response.status === 422) {
        Notify.create({
          message: error.response.data.message,
          timeout: 3000,
          color: "negative",
        });
        // store.commit('validationErrors/setErrors', error.response.data.errors)
        setTimeout(() => {
          // store.commit('validationErrors/setErrors', {})
        }, 5000);
      } else if (error.response && error.response.status === 401) {
        Notify.create({
          progress: true,
          message: "Session Time Out. Please re-login to continue.",
          timeout: 2000,
          color: "negative",
        });
        setTimeout(() => {
          LocalStorage.remove('access_token')
          LocalStorage.remove('current_tenant')
          LocalStorage.remove('refresh_token')
          window.location.reload(false);
          store.commit("auth/setUser", null);
        }, 2000)
      } else if (error.response && error.response.status === 400) {
        // oauth.logout()
        if (error.response.data.message) {
          Notify.create({
            message: error.response.data.message,
            timeout: 3000,
            color: "negative",
          });
          // store.commit('validationErrors/setErrors', error.response.data.errors)
        }
      } else if (error.response && error.response.status === 500) {
        Notify.create({
          // message: "Internal Server Error. Contact System Admin.",
          message: error.response.data.message,
          timeout: 3000,
          color: "negative",
        });
      } else if (error.response && error.response.status === 403) {
        Notify.create({
          message: error.response.data.message,
          timeout: 3000,
          color: "negative",
        });
      } else {
        Notify.create({
          message: error.response?.data?.message,
          timeout: 3000,
          color: "negative",
        });
      }
      return Promise.reject(error);
    }
  );
  app.config.globalProperties.$axios = axios;
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$axiosInstance = axiosInstance;
  app.config.globalProperties.$_ = _;
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
});

export {axiosInstance};
