<template>
  <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-2xl shadow-2xl">
    <h2 class="text-2xl font-bold text-center text-gray-800">Login</h2>
    <form @submit.prevent="handleLogin" class="space-y-4">
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input
          id="email"
          type="email"
          v-model="email" required
          placeholder="email kamu"
          class="w-full px-4 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input
          id="password"
          type="password"
          v-model="password" required
          placeholder="password"
          class="w-full px-4 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div v-if="errorMessage" class="text-red-500 text-sm">
        {{ errorMessage }}
      </div>

      <button type="submit"
        class="w-full py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition"
      >
        Login
      </button>
    </form>
  </div>
</template>

<script setup>
  import router from '@/router';
  import { ref } from 'vue'
  import axios from 'axios'

  const baseUrl = import.meta.env.VITE_API_BASE_URL;

  const email = ref('')
  const password = ref('')
  const errorMessage = ref('')

  const handleLogin = async () => {
    errorMessage.value = ''

    try {
      const response = await axios.post(`${baseUrl}/login`, {
        email: email.value,
        password: password.value
      })

      if (response.status === 200 && response.data.access_token) {
        console.log('Login success:', response.data)

        localStorage.setItem('token', response.data.access_token)

        router.push({ name: 'CsAntrean' })
      } else {
        errorMessage.value = 'Login gagal. Coba lagi.'
      }
    } catch (error) {
      console.error(error)
      if (error.response) {
        errorMessage.value = error.response.data.message || 'Login gagal.'
      } else {
        errorMessage.value = 'Terjadi kesalahan jaringan.'
      }
    }
  }
</script>
