<template>
    <div class="w-72 bg-green-600 text-white h-screen fixed top-0 left-0 flex flex-col">
      <div class="flex items-center justify-center gap-3 py-6 border-b border-white/20">
        <img src="@/assets/logoAntrio.png" alt="Logo Antrio" class="h-8 w-8 object-contain" />
        <h1 class="text-2xl font-bold">ANTRIO</h1>
      </div>
  
      <div class="flex items-center gap-4 px-6 py-4 border-b border-white/20">
        <div>
          <h3 class="text-lg font-semibold">{{ user.name }}</h3>
          <p class="text-sm text-white/80">Super Admin</p>
        </div>
      </div>
  
      <nav class="flex flex-col px-4 py-4 gap-2 flex-1">
        <router-link
          to="/admin/service"
          class="flex items-center gap-3 px-4 py-2 rounded-md transition hover:bg-white/10"
          :class="{ 'bg-white/10 font-bold': $route.path === '/admin/service' }"
        >
          <span>
            <i class="bi bi-gear-fill mr-2"></i>
            Manage Services
          </span>
        </router-link>
  
        <router-link
          to="/admin/manage-cs"
          class="flex items-center gap-3 px-4 py-2 rounded-md transition hover:bg-white/10"
          :class="{ 'bg-white/10 font-bold': $route.path === '/admin/manage-cs' }"
        >
          <span>
            <i class="bi bi-person-fill-gear mr-2"></i>
            Manage CS
          </span>
        </router-link>
  
        <router-link
          to="/admin/manage-user"
          class="flex items-center gap-3 px-4 py-2 rounded-md transition hover:bg-white/10"
          :class="{ 'bg-white/10 font-bold': $route.path === '/admin/manage-user' }"
        >
          <span>
            <i class="bi bi-people-fill mr-2"></i>
            Manage User
          </span>
        </router-link>

        <router-link
          to="/admin/antrian"
          class="flex items-center gap-3 px-4 py-2 rounded-md transition hover:bg-white/10"
          :class="{ 'bg-white/10 font-bold': $route.path === '/admin/antrian' }"
        >
          <span>
            <i class="bi bi-chat-right-dots-fill mr-2"></i>
            Antrean
          </span>
        </router-link>
  
        <button
          class="flex items-center gap-3 px-4 py-2 rounded-md transition hover:bg-red-600 mt-auto"
          @click="handleLogout"
        >
          <span>
            <i class="bi bi-door-closed-fill mr-2"></i>
            Logout
          </span>
        </button>
      </nav>
  
    </div>
</template>
  
<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const baseUrl = import.meta.env.VITE_API_BASE_URL;
const router = useRouter()
const user = ref({})

const getUser = async () => {
  const token = localStorage.getItem('access_token')
  if (!token) {
    router.push('/login')
    return
  }

  try {
    const response = await axios.get(`${baseUrl}/me`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })

    user.value = response.data
  } catch (error) {
    console.error('Gagal mengambil user:', error)
    localStorage.removeItem('access_token')
    localStorage.removeItem('user_role')
    router.push('/login')
  }
}

const handleLogout = async () => {
  try {
    const token = localStorage.getItem('access_token')

    await axios.post(`${baseUrl}/logout`, {}, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })

    localStorage.removeItem('access_token')
    localStorage.removeItem('user_role')
    router.push('/login')
  } catch (error) {
    console.error('Logout gagal:', error)
  }
}

onMounted(() => {
  getUser()
})
</script>

  
  