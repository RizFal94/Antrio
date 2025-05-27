import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '@/views/LoginView.vue'
import RegisterView from '@/views/RegisterView.vue'
import CsHomeView from '@/components/cs/CsHomeView.vue'
import AntrianDetailView from '@/views/AntrianDetailView.vue'
import CsAntrean from '@/views/cs/CsAntrean.vue'
import CsAction from '@/views/cs/CsAction.vue'
import CsMeja from '@/views/cs/CsMeja.vue'
import AdminHomeView from '@/components/admin/AdminHomeView.vue'
import AdminAntrean from '@/views/admin/AdminAntrean.vue'
import AdminManageCs from '@/views/admin/AdminManageCs.vue'
import AdminService from '@/views/admin/AdminService.vue'
import AdminUser from '@/views/admin/AdminUser.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'home', component: HomeView },
    { path: '/antrian', name: 'antrian-detail', component: AntrianDetailView },

    { path: '/login', name: 'login', component: LoginView },
    { path: '/register', name: 'register', component: RegisterView },

    {
      path: '/cs',
      component: CsHomeView,
      meta: { requiresAuth: true, role: 'cs' },
      children: [
        { path: 'meja', name: 'CsMeja', component: CsMeja },
        { path: 'action', name: 'CsAction', component: CsAction },
        { path: 'antrian', name: 'CsAntrean', component: CsAntrean },
        { path: '', name: 'CsDefault', redirect: { name: 'CsMeja' } },
      ]
    },

    {
      path: '/admin',
      component: AdminHomeView,
      meta: { requiresAuth: true, role: 'admin' },
      children: [
        { path: 'service', name: 'AdminService', component: AdminService },
        { path: 'manage-cs', name: 'AdminManageCs', component: AdminManageCs },
        { path: 'manage-user', name: 'AdminUser', component: AdminUser },
        { path: 'antrian', name: 'AdminAntrean', component: AdminAntrean },
        { path: '', name: 'AdminDefault', redirect: { name: 'AdminService' } },
      ]
    },
  ]
})

// Navigation Guard
router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
  const token = localStorage.getItem('access_token')
  const userRole = localStorage.getItem('user_role')

  if ((to.name === 'login' || to.name === 'register') && token) {
    if (userRole === 'admin') return next({ name: 'AdminService' })
    if (userRole === 'cs') return next({ name: 'CsAntrean' })
    return next({ name: 'home' })
  }

  if (requiresAuth) {
    if (!token || !userRole) {
      return next({ name: 'login' })
    }

    if (to.meta.role && to.meta.role !== userRole) {
      return next({ name: 'login' })
    }
  }

  next()
})

export default router
