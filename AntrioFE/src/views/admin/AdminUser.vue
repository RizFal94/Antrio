<template>
  <div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-green-600 text-2xl font-bold mb-6">DAFTAR USER</h1>

    <div class="flex justify-end mr-10">
      <button
        @click="showModal = true"
        class="mb-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"
      >
        + Tambah User
      </button>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white shadow-md rounded-xl">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="py-3 px-4 text-left">ID</th>
            <th class="py-3 px-4 text-left">NAMA</th>
            <th class="py-3 px-4 text-left">EMAIL</th>
            <th class="py-3 px-4 text-left">ROLE</th>
            <th class="py-3 px-4 text-left">AKSI</th>
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
                  @click="openDeleteConfirm(user)"
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

    <div
      v-if="showModal"
      class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50"
      style="background-color: rgba(0, 0, 0, 0.2);"
    >
      <div class="bg-white p-6 rounded-xl w-full max-w-md shadow-lg relative">
        <h2 class="text-xl font-bold mb-4">Tambah User</h2>
        <form @submit.prevent="submitForm">
          <div class="mb-3">
            <label class="block mb-1">Nama</label>
            <input v-model="newUser.name" type="text" class="w-full border rounded p-2" required />
          </div>
          <div class="mb-3">
            <label class="block mb-1">Email</label>
            <input v-model="newUser.email" type="email" class="w-full border rounded p-2" required />
          </div>
          <div class="mb-3">
            <label class="block mb-1">Password</label>
            <input v-model="newUser.password" type="password" class="w-full border rounded p-2" required />
          </div>
          <div class="mb-3">
            <label class="block mb-1">Konfirmasi Password</label>
            <input v-model="newUser.password_confirmation" type="password" class="w-full border rounded p-2" required />
          </div>
          <p v-if="passwordMismatch" class="text-red-600 text-sm mt-1">
            Password dan konfirmasi password tidak cocok.
          </p>

          <div class="flex justify-end gap-2">
            <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
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

    <div
      v-if="showDeleteConfirm"
      class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50"
      style="background-color: rgba(0, 0, 0, 0.2);"
    >
      <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full text-center">
        <p class="mb-4 text-lg">Apakah Anda yakin ingin menghapus user</p>
        <strong>{{ selectedUserToDelete?.name }}</strong> ?
        <div class="flex justify-center gap-4 mt-4">
          <button
            @click="confirmDelete"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition w-18"
          >
            Ya
          </button>
          <button
            @click="cancelDelete"
            class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition w-18"
          >
            Tidak
          </button>
        </div>
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
    await axios.post(`${baseUrl}/admin/tambah-user`, newUser.value)
    closeModal()
    await fetchUsers()
  } catch (error) {
    console.error('Gagal menambahkan user:', error)
  }
}

const showDeleteConfirm = ref(false)
const selectedUserToDelete = ref(null)

const openDeleteConfirm = (user) => {
  selectedUserToDelete.value = user
  showDeleteConfirm.value = true
}

const cancelDelete = () => {
  selectedUserToDelete.value = null
  showDeleteConfirm.value = false
}

const confirmDelete = async () => {
  try {
    if (selectedUserToDelete.value) {
      await axios.delete(`${baseUrl}/admin/hapus-user/${selectedUserToDelete.value.id}`)
      await fetchUsers()
    }
  } catch (error) {
    console.error('Gagal menghapus user:', error)
  } finally {
    cancelDelete()
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