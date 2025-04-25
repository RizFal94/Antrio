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
              <th class="py-3 px-4 text-left">Aksi</th>
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
              <td class="py-2 px-4">
                <button
                  v-if="!cs.status"
                  @click="aktifkan(cs.id)"
                  class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                >
                  Aktifkan
                </button>
                <span v-else class="text-sm text-gray-400">Sudah Aktif</span>
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
  
  const fetchCustomerServices = async () => {
    try {
      const response = await axios.get(`${baseUrl}/customer-service/all`)
      customerServices.value = response.data
    } catch (error) {
      console.error('Gagal memuat data customer service:', error)
    }
  }
  
  const aktifkan = async (id) => {
    try {
      await axios.post(`${baseUrl}/customer-service/aktifkan/${id}`)
      await fetchCustomerServices()
    } catch (error) {
      console.error('Gagal mengaktifkan CS:', error.response?.data?.message || error.message)
    }
  }
  
  onMounted(fetchCustomerServices)
</script>
  