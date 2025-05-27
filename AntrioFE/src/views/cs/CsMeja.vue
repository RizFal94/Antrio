<template>
  <div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-green-600 text-2xl font-bold mb-12">DAFTAR CUSTOMER SERVICE</h1>
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white shadow-md rounded-xl">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="py-3 px-4 text-left">ID</th>
            <th class="py-3 px-4 text-left">Customer Service</th>
            <th class="py-3 px-4 text-left">Prefix</th>
            <th class="py-3 px-4 text-left">Status</th>
            <th class="py-3 px-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="cs in customerServices"
            :key="cs.id"
            class="border-t hover:bg-gray-50"
          >
            <td class="py-2 px-4">{{ cs.id }}</td>
            <td class="py-2 px-4">{{ cs.name }}</td>
            <td class="py-2 px-4">{{ cs.prefix }}</td>
            <td class="py-2 px-4">
              <span
                :class="cs.status ? 'text-green-600 font-semibold' : 'text-gray-500'"
              >
                {{ cs.status ? 'Aktif' : 'Non-Aktif' }}
              </span>
            </td>
            <td class="py-2 px-4 text-center">
              <button
                v-if="!cs.status"
                @click="aktifkan(cs.id)"
                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
              >
                Aktifkan
              </button>

              <button
                v-else-if="cs.user_id == userId"
                @click="nonaktifkan(cs.id)"
                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
              >
                Nonaktifkan
              </button>

              <span v-else class="text-gray-400 italic">Sedang aktif oleh user lain</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
  
<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const baseUrl = import.meta.env.VITE_API_BASE_URL;
const customerServices = ref([])

const token = localStorage.getItem('access_token');
const userId = localStorage.getItem('user_id')

const fetchCustomerServices = async () => {
  try {
    const response = await axios.get(`${baseUrl}/customer-service/all`)
    // Urutkan data berdasarkan id ascending (dari kecil ke besar)
    customerServices.value = response.data.sort((a, b) => a.id - b.id)
  } catch (error) {
    console.error('Gagal memuat data customer service:', error)
  }
}

const aktifkan = async (id) => {
  try {
    const response = await axios.post(`${baseUrl}/customer-service/aktifkan/${id}`, null, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });

    // Simpan user_id dari response ke localStorage jika ada
    const userId = response.data?.data?.user_id
    if (userId) {
      localStorage.setItem('user_id', userId)
      console.log('user_id disimpan:', userId)
    }

    await fetchCustomerServices()
  } catch (error) {
    console.error('Gagal mengaktifkan CS:', error.response?.data?.message || error.message)
  }
}

const nonaktifkan = async (id) => {
  try {
    await axios.post(`${baseUrl}/customer-service/non-aktifkan/${id}`, null, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    await fetchCustomerServices()
  } catch (error) {
    console.error('Gagal menonaktifkan CS:', error.response?.data?.message || error.message)
  }
}

onMounted(fetchCustomerServices)
</script>