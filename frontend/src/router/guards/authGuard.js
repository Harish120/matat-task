import { useAuthStore } from 'stores/auth';

export function authGuard(to, from, next) {
  const authStore = useAuthStore();

  if (!authStore.isAuthenticated) {
    next('/login'); // Redirect to the login page if not authenticated
  } else {
    next(); // Proceed to the route if authenticated
  }
}
