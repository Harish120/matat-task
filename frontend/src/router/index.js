import { route } from 'quasar/wrappers'
import { createRouter, createMemoryHistory, createWebHistory, createWebHashHistory } from 'vue-router'
import routes from './routes'
import {authGuard} from "src/router/guards/authGuard";
import {useAuthStore} from "stores/auth";

/*
 * If not building with SSR mode, you can
 * directly export the Router instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Router instance.
 */

export default route(function (/* { store, ssrContext } */) {
  const createHistory = process.env.SERVER
    ? createMemoryHistory
    : (process.env.VUE_ROUTER_MODE === 'history' ? createWebHistory : createWebHashHistory)

  const Router = createRouter({
    scrollBehavior: () => ({ left: 0, top: 0 }),
    routes,

    // Leave this as is and make changes in quasar.conf.js instead!
    // quasar.conf.js -> build -> vueRouterMode
    // quasar.conf.js -> build -> publicPath
    history: createHistory(process.env.VUE_ROUTER_BASE)
  })


// Global navigation guard to check authentication
  Router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    // Check if the route requires authentication
    if (to.matched.some(record => record.beforeEnter === authGuard && !authStore.isAuthenticated)) {
      next('/login'); // Redirect to login if not authenticated
    } else {
      next(); // Proceed to the route if authenticated or not protected
    }
  });

  return Router
})
