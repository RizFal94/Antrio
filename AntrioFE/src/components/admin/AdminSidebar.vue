<template>
    <div class="w-72 bg-green-600 text-white h-screen fixed top-0 left-0 flex flex-col">
      <div class="text-center py-6 border-b border-white/20">
        <h1 class="text-2xl font-bold">ANTRIO</h1>
      </div>
  
      <div class="flex items-center gap-4 px-6 py-4 border-b border-white/20">
        <div>
          <h3 class="text-lg font-semibold">{{ user.name }}</h3>
          <p class="text-sm text-white/80">ADMIN</p>
        </div>
      </div>
  
      <nav class="flex flex-col px-4 py-4 gap-2 flex-1">
        <router-link
          to="/admin/service"
          class="flex items-center gap-3 px-4 py-2 rounded-md transition hover:bg-white/10"
          :class="{ 'bg-white/10 font-bold': $route.path === '/admin/service' }"
        >
          <span>Services</span>
        </router-link>
  
        <router-link
          to="/admin/manageCs"
          class="flex items-center gap-3 px-4 py-2 rounded-md transition hover:bg-white/10"
          :class="{ 'bg-white/10 font-bold': $route.path === '/admin/manageCs' }"
        >
          <span>Manage CS</span>
        </router-link>
  
        <router-link
          to="/admin/manageUser"
          class="flex items-center gap-3 px-4 py-2 rounded-md transition hover:bg-white/10"
          :class="{ 'bg-white/10 font-bold': $route.path === '/admin/manageUser' }"
        >
          <span>Manage User</span>
        </router-link>

        <router-link
          to="/admin/antrian"
          class="flex items-center gap-3 px-4 py-2 rounded-md transition hover:bg-white/10"
          :class="{ 'bg-white/10 font-bold': $route.path === '/admin/antrian' }"
        >
          <span>Antrean</span>
        </router-link>
  
        <button
          class="flex items-center gap-3 px-4 py-2 rounded-md transition hover:bg-red-600 mt-auto"
          @click="handleLogout"
        >
          <span>Logout</span>
        </button>
      </nav>
  
    </div>
</template>
  
<script setup>
    import { onMounted, ref, computed } from 'vue'
    import { useRouter } from 'vue-router'
    import axios from 'axios'
  
    const baseUrl = import.meta.env.VITE_API_BASE_URL;
    const router = useRouter()
    const user = ref([])
  
    const getUser = async () => {
      try {
        const response = await axios.get(`${baseUrl}/me`)
        user.value = response.data
      } catch (error) {
        console.error('Gagal mengambil user:', error)
      }
    }
  
    const handleLogout = () => {
      axios.post(`${baseUrl}/logout`).then(() => {
        router.push('login')
      })
    }
  
    onMounted(() => {
      getUser()
    })
</script>
  
  