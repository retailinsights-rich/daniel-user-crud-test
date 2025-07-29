import { createRouter, createWebHistory } from 'vue-router'
import Login from '../components/Login.vue'
import Register from '../components/Register.vue'
import Dashboard from '../components/Dashboard.vue'
import PasswordReset from '../components/PasswordReset.vue'
import TicTacToe from '../components/TicTacToe.vue'
import MainLayout from '../layout/MainLayout.vue'

import { useAuthStore } from '@/store/auth';
import axios from '@/axios'
const routes = [
    {
        path: '/',
        name: 'root',
        component: MainLayout,
        children: [
            { path: '/dashboard', name: 'Dashboard', component: Dashboard, meta: { requiresAuth: true } },
            { path: '/ttt', name: 'Tic Tac Toe', component: TicTacToe, meta: { requiresAuth: true } },
            { path: '/password-reset', name: 'Password Reset', component: PasswordReset, meta: { requiresAuth: true } },
        ]
    },
    {
        path: '/password-reset',
        name: 'Password Reset',
        component: PasswordReset,
        meta: { requiresAuth: true }
    },
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/register',
        name: 'Register',
        component: Register
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach(async (to, from, next) => {
    const auth = useAuthStore();

    await auth.fetchUser();

    if (to.path === '/') {
        return next(auth.loggedIn ? '/dashboard' : '/login');
    }
 
    if (to.meta.requiresAuth && !auth.loggedIn) {
        return next('/login');
    }

    if (to.meta.requiresAuth && auth.mustResetPassword && to.path !== '/password-reset') {
        return next('/password-reset');
    }
  
    return next();
});

export default router