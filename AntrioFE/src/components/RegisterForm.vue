<template>
  <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-2xl shadow-2xl">
    <h2 class="text-2xl font-bold text-center text-gray-800">Register</h2>
    <form @submit.prevent="handleRegister" class="space-y-4">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
        <input
          id="name"
          type="text"
          v-model="name" required
          placeholder="nama lengkap"
          class="w-full px-4 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input
          id="email"
          type="email"
          v-model="email" required
          placeholder="email"
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

      <div>
        <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input
          id="confirmPassword"
          type="password"
          v-model="confirmPassword" required
          placeholder="konfirmasi password"
          class="w-full px-4 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div v-if="errorMessage" class="text-red-500 text-sm">
        {{ errorMessage }}
      </div>

      <button
        type="submit"
        class="w-full py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition"
      >
        Register
      </button>
    </form>
  </div>
</template>

<script setup>
  import router from '@/router';
  import { ref } from 'vue'

  const baseUrl = import.meta.env.VITE_API_BASE_URL;

  const name = ref('')
  const email = ref('')
  const password = ref('')
  const confirmPassword = ref('')
  const errorMessage = ref('')

  const handleRegister = async () => {
    errorMessage.value = ''  

    if (password.value !== confirmPassword.value) {
      errorMessage.value = 'Password dan konfirmasi password tidak cocok.'
      return
    }

    try {
      const response = await fetch(`${baseUrl}/register`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          name: name.value,
          email: email.value,
          password: password.value,
          password_confirmation: confirmPassword.value
        })
      });

    console.log('Register success:', response.data)
    router.push({name: "login"})

    } catch (error) {
      if (error.response) {
        errorMessage.value = error.response.data.message || 'Register gagal.'
      } else {
        errorMessage.value = 'Terjadi kesalahan jaringan.'
      }
    }
  }
</script>
