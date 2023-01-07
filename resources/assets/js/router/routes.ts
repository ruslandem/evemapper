import { useAuthStore } from '@/stores/auth';
import {
  NavigationGuardNext,
  RouteLocationNormalized,
  RouteRecordRaw
} from 'vue-router';
import { appConfig } from '@/config';

const admins: string[] = appConfig.admins;

const checkAuth = (
  to: RouteLocationNormalized,
  from: RouteLocationNormalized,
  next: NavigationGuardNext
) => {
  const auth = useAuthStore();
  if (!auth.isAuthenticated) {
    next('login');
    return false;
  }
  next();
};

const checkAdmin = (
  to: RouteLocationNormalized,
  from: RouteLocationNormalized,
  next: NavigationGuardNext
) => {
  const auth = useAuthStore();
  if (!auth.isAuthenticated) {
    next('login');
    return false;
  }
  if (auth.character && !admins.includes(auth.character.name)) {
    next('login');
    return false;
  }
  next();
};

const routes: RouteRecordRaw[] = [
  {
    name: 'home',
    path: '/',
    component: () => import('@/pages/HomePage/index.vue')
  },
  {
    name: 'locate',
    path: '/locate',
    component: () => import('@/pages/LocatePage/index.vue'),
    beforeEnter: checkAuth
  },
  {
    name: 'route',
    path: '/route',
    component: () => import('@/pages/RoutePage/index.vue'),
    beforeEnter: checkAuth
  },
  {
    name: 'login',
    path: '/login',
    component: () => import('@/pages/LoginPage.vue')
  },
  {
    name: 'license',
    path: '/license',
    component: () => import('@/pages/LicensePage.vue')
  },
  {
    name: 'admin',
    path: '/admin',
    beforeEnter: checkAdmin,
    component: () => import('@/pages/AdminPage/index.vue')
  }
];

export default routes;
