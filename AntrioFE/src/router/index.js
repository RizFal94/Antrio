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
    { path: '/cs', component: CsHomeView,
      children: [
        { path: 'antrian', name: 'CsAntrean', component: CsAntrean, },
        { path: 'action', name: 'CsAction', component: CsAction, },
        { path: 'meja', name: 'CsMeja', component: CsMeja, },
        { path: '', redirect: '/cs/antrian', },
      ],
    },
    { path: '/admin', component: AdminHomeView,
      children: [
        { path: 'antrian', name: 'AdminAntrean', component: AdminAntrean, },
        { path: 'service', name: 'AdminService', component: AdminService, },
        { path: 'manage-cs', name: 'AdminManageCs', component: AdminManageCs, },
        { path: 'manage-user', name: 'AdminUser', component: AdminUser, },
        { path: '', redirect: '/admin/service', },
      ],
    },
  ],
})

export default router
