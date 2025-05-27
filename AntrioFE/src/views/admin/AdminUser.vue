<template>
  <div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-green-600 text-2xl font-bold mb-6">DAFTAR USER</h1>

    <button
      @click="showModal = true"
      class="mb-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"
    >
      Tambah User
    </button>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white shadow-md rounded-xl">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="py-3 px-4 text-left">ID</th>
            <th class="py-3 px-4 text-left">Nama</th>
            <th class="py-3 px-4 text-left">Email</th>
            <th class="py-3 px-4 text-left">Role</th>
            <th class="py-3 px-4 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="user in users"
            :key="user.id"
            class="border-t hover:bg-gray-50"
          >
            <td class="py-2 px-4">{{ user.id }}</td>
            <td class="py-2 px-4">{{ user.name }}</td>
            <td class="py-2 px-4">{{ user.email }}</td>
            <td class="py-2 px-4">{{ user.role }}</td>
            <td class="py-2 px-4">
              <template v-if="user.role === 'admin'">
                -
              </template>
              <template v-else>
                <button
                  @click="hapusUser(user.id)"
                  class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
                >
                  Hapus
                </button>
              </template>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Tambah Akun -->
    <div
      v-if="showModal"
      class="fixed inset-0 flex items-center justify-center z-50"
      style="background-color: rgba(0, 0, 0, 0.2);"
    >
      <div class="bg-white p-6 rounded-xl w-full max-w-md shadow-lg relative">
        <h2 class="text-xl font-bold mb-4">Tambah User</h2>
        <form @submit.prevent="submitForm">
          <div class="mb-3">
            <label class="block mb-1">Nama</label>
            <input
              v-model="newUser.name"
              type="text"
              class="w-full border rounded p-2"
              required
            />
          </div>
          <div class="mb-3">
            <label class="block mb-1">Email</label>
            <input
              v-model="newUser.email"
              type="email"
              class="w-full border rounded p-2"
              required
            />
          </div>
          <div class="mb-3">
            <label class="block mb-1">Password</label>
            <input
              v-model="newUser.password"
              type="password"
              class="w-full border rounded p-2"
              required
            />
          </div>
          <div class="mb-3">
            <label class="block mb-1">Konfirmasi Password</label>
            <input
              v-model="newUser.password_confirmation"
              type="password"
              class="w-full border rounded p-2"
              required
            />
          </div>
          <p v-if="passwordMismatch" class="text-red-600 text-sm mt-1">
            Password dan konfirmasi password tidak cocok.
          </p>

          <div class="flex justify-end gap-2">
            <button
              type="button"
              @click="closeModal"
              class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
            >
              Batal
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 disabled:opacity-50"
              :disabled="!isFormValid"
            >
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const baseUrl = import.meta.env.VITE_API_BASE_URL

const users = ref([])
const showModal = ref(false)

const newUser = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const closeModal = () => {
  showModal.value = false
  newUser.value = {
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  }
}

const fetchUsers = async () => {
  try {
    const response = await axios.get(`${baseUrl}/admin/users`)
    users.value = response.data.data
  } catch (error) {
    console.error('Gagal memuat data user:', error)
  }
}

const submitForm = async () => {
  try {
    const payload = {
      name: newUser.value.name,
      email: newUser.value.email,
      password: newUser.value.password,
      password_confirmation: newUser.value.password_confirmation,
    }
    await axios.post(`${baseUrl}/admin/tambah-user`, payload)
    closeModal()
    await fetchUsers()
  } catch (error) {
    console.error('Gagal menambahkan user:', error)
  }
}

const hapusUser = async (id) => {
  if (confirm('Yakin ingin menghapus user ini?')) {
    try {
      await axios.delete(`${baseUrl}/admin/hapus-user/${id}`)
      await fetchUsers()
    } catch (error) {
      console.error('Gagal menghapus user:', error)
    }
  }
}

const isFormValid = computed(() => {
  return (
    newUser.value.name &&
    newUser.value.email &&
    newUser.value.password &&
    newUser.value.password_confirmation &&
    newUser.value.password === newUser.value.password_confirmation
  )
})

const passwordMismatch = computed(() => {
  return (
    newUser.value.password &&
    newUser.value.password_confirmation &&
    newUser.value.password !== newUser.value.password_confirmation
  )
})

onMounted(fetchUsers)
</script>
